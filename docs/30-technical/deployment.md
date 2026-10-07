# Deployment

| | |
| --- | --- |
| **Document** | Deployment, Configuration and Operations — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | **`docs/deployment.md`** (Phase 1). That document is removed; its operational content is preserved here. |

**Scope.** How Bulbula is deployed and run: the runtime, the document root,
installation, configuration, secrets, migrations, the deployment sequence,
rollback, permissions, storage, logs, health checks, backups, cron and
production safety.

**The hard constraint.** Deployment targets **shared hosting**: Apache with
`.htaccess`, PHP 8.4, MariaDB, cron, a filesystem. **No Docker, no
Kubernetes, no root access, no long-running daemon, no process supervisor**
(TRD TR-183, NG-10).

**Currently deployed:** one development host,
`bulbula.arkeonethiopia.com` (cPanel). **There is no production environment
yet.** Nothing in this document is a production procedure; it is the design
production will follow.

---

## 1. Principles

| ID | Principle | Source |
| --- | --- | --- |
| DP-1 | **The repository is the source of truth.** Nothing a deployed environment needs may exist only on a server | TRD TR-180 |
| DP-2 | **No secret is ever committed.** Not in code, not in configuration, not in a fixture, not in documentation | TRD TR-188, D-23 |
| DP-3 | A deployment is **reproducible**: the same commit plus the same environment configuration yields the same running system | TRD TR-181 |
| DP-4 | A deployment is **reversible** — §8 | TRD TR-182 |
| DP-5 | The environment is configuration, never a code branch. There is no `if (production)` scattered through the code | TRD TR-193 |
| DP-6 | Failures are **loud**. A misconfigured system fails to boot rather than running degraded in silence | architecture §12 |
| DP-7 | **Never patch a server by hand.** Change the repository and deploy | DP-1 |
| DP-8 | Nothing outside `public/` is reachable over HTTP | TRD TR-184 |

---

## 2. Runtime requirements

| Requirement | Value | Verified in |
| --- | --- | --- |
| PHP | **`^8.4`** | `composer.json` |
| Extensions | `ext-pdo`, `ext-pdo_mysql` | `composer.json` |
| Database | MariaDB, `utf8mb4` | TRD TR-165 |
| Web server | Apache with `mod_rewrite` and `.htaccess` overrides | `public/.htaccess` |
| Composer | 2.x | — |
| Cron | Available | §13 |
| Writable path | `storage/` | §10 |

**Runtime dependencies are four libraries** — `nikic/fast-route`,
`monolog/monolog`, `psr/log`, `vlucas/phpdotenv`. Everything else in
`composer.json` is development tooling and is **not installed in
production** (§4).

**On cPanel the default shell `php` is 8.2.** The 8.4 binary must be called
explicitly:

| Purpose | Path on the development host |
| --- | --- |
| PHP 8.4 CLI | `/opt/cpanel/ea-php84/root/usr/bin/php` |
| Composer | `/home/arkeonet/composer.phar` |
| Git | `/usr/local/cpanel/3rdparty/lib/path-bin/git` |

The front controller reports the interpreter version it was served by, so a
wrong-version request names the condition outright instead of failing
cryptically.

---

## 3. Document root and the front controller

**The web server serves `public/`, never the repository root.**

```text
bulbula.et/                      <- repository root, NOT web accessible
├── bin/ config/ database/ docs/ resources/ routes/ src/ tests/ vendor/
├── storage/                     <- runtime state, NOT web accessible
├── composer.json composer.lock .env
└── public/                      <- document root
    ├── .htaccess
    ├── index.php                <- the only entry point
    └── assets/
```

| ID | Rule |
| --- | --- |
| DR-1 | `.env`, `composer.json`, `composer.lock`, `src/`, `config/`, `database/`, `resources/`, `tests/`, `vendor/`, `storage/` and `docs/` all stay **outside** the document root (TRD TR-184) |
| DR-2 | `public/index.php` is the **only** PHP entry point (TRD TR-185) |
| DR-3 | If the document root cannot be changed from the repository root, **fix the vhost**. Do not work around it with rewrites that expose project files |
| DR-4 | Directory listings are off |
| DR-5 | Static assets under `public/assets/` are served by Apache and never routed through PHP |

### 3.1 `public/.htaccess`

Part of the repository (DP-1):

```apache
DirectoryIndex index.php

<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

| Request | Result |
| --- | --- |
| `/` | `index.php` — the directory index *is* the front controller |
| `/health`, `/api/v1/health` | `index.php` with the original URI preserved |
| `/assets/css/styles.css` | Served by Apache, never routed |

The rewrite is **internal**, so `REQUEST_URI` keeps the path the client
asked for — which is what the router matches on.

> **Do not reintroduce a static `index.html` in `public/`.** Apache resolves
> a directory index *before* the rewrite runs, so a static file there
> silently takes over `/`; and a rewrite pointing at `/public/index.html`
> makes the application receive that literal path and answer
> `404 No route matches [/public/index.html]`.

> **Keep `.htaccess` minimal.** Shared hosts grant only a subset of override
> classes. cPanel and EA4 commonly withhold the one that permits `Options`,
> so an `Options -Indexes` line — harmless on a self-managed server — takes
> the whole document root down with a 500 on **every** URL, static files
> included. Turn directory listings off in the hosting panel instead (DR-4).

> **The cPanel PHP-version handler block** (`# php -- BEGIN cPanel-generated
> handler`) is written into `public/.htaccess` by MultiPHP Manager and is
> **not** in version control unless committed. A deploy that resets the
> working tree deletes it, and every PHP URL then 500s under an older
> interpreter. The block therefore lives in `public/.htaccess` **in this
> repository**, and that is the copy to change when the PHP version changes.

---

## 4. Installation

```bash
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
```

On the development host:

```bash
/opt/cpanel/ea-php84/root/usr/bin/php /home/arkeonet/composer.phar install \
    --no-dev --prefer-dist --no-interaction --optimize-autoloader
```

| ID | Rule |
| --- | --- |
| IN-1 | `--no-dev` — **no development tooling in production** (PHPUnit, PHPStan, Rector, Pint, Infection, Insights) |
| IN-2 | `--optimize-autoloader` — classmap autoloading, measurably faster per request |
| IN-3 | `composer.lock` is committed and is **authoritative**. `composer update` is never run on a server (DP-3) |
| IN-4 | `vendor/` is generated on the server and **never committed** |
| IN-5 | Composer runs as the application user, never as root |

---

## 5. Configuration

### 5.1 How it works

```text
.env  →  Bulbula\Config\Env  →  Bulbula\Config\Config  →  config/*.php  →  application
```

| ID | Rule | Enforced by |
| --- | --- | --- |
| CF-1 | All environment-specific values come from the environment, never from code (TRD TR-190) | — |
| CF-2 | **`Env` is reachable only from `Bulbula\Config`.** No other namespace reads `getenv`, `$_ENV`, `$_SERVER`, `$_GET` or `$_POST` | `tests/Arch/ArchTest.php` |
| CF-3 | `.env.example` lists **every** variable with a safe placeholder and is committed (TRD TR-191) | — |
| CF-4 | `.env` is **never** committed | `.gitignore` |
| CF-5 | A **missing required** variable fails the boot loudly (TRD TR-192) | architecture §12 |
| CF-6 | Configuration is read at boot; nothing re-reads the environment mid-request | CF-2 |
| CF-7 | Adding a variable means updating `.env.example` **in the same commit** | — |

### 5.2 Variable classes

| Class | Examples | Secret? |
| --- | --- | --- |
| Environment | application environment, debug flag, base URL, timezone | No |
| Database | host, port, name, user, **password** | **Password: yes** |
| Logging | channel, level, path | No |
| Mail | provider endpoint, sender address, **credential** (D-41) | **Credential: yes** |
| Google sign-in | client id, **client secret** (D-48) | **Secret: yes** |
| Maps | **API key** (D-21) | **Yes** |
| Telegram | **bot token** (D-33) | **Yes** |
| Tunables | cache lifetimes, rate limits, page sizes, session lifetimes | No |

### 5.3 Secrets

| ID | Rule |
| --- | --- |
| SC-1 | **No secret in Git, ever** (DP-2). `.gitleaks.toml` and the security workflow enforce this in CI |
| SC-2 | Secrets reach the server only through `.env`, created once on the host from `.env.example` |
| SC-3 | `.env` is readable only by the application user (§10) |
| SC-4 | **No secret is logged, echoed in an error, or returned in a response** (TRD TR-25) |
| SC-5 | A secret committed by accident is **rotated**, not merely removed from history |
| SC-6 | Rotation requires no code change — every secret is a variable (CF-1) |
| SC-7 | **Production secret custody is Open (D-23).** This document states the mechanism; it does not assign custody |

---

## 6. Database and migrations

### 6.1 The mechanism

| Fact | Detail |
| --- | --- |
| Location | `database/migrations/` |
| Naming | `YYYY_MM_DD_HHMMSS_description.php` |
| Shape | Returns an anonymous class extending `Bulbula\Database\Migrations\Migration` |
| Tracking | Table `migrations` (`migration`, `batch`, `executed_at`) |
| Transactions | **DDL is never wrapped in a transaction** — MariaDB implicitly commits it, so a wrapper would be a lie |
| Portability | Tests run against in-memory SQLite, so migration SQL stays portable |

### 6.2 Commands

```bash
php bin/console migration:status   # what has run, what is pending
php bin/console migrate            # apply pending migrations
```

### 6.3 Rules

| ID | Rule | Source |
| --- | --- | --- |
| MG-1 | Schema changes happen **only** through migrations. No manual DDL, ever (TRD TR-174) | DP-1 |
| MG-2 | Migrations are **forward-only** in production. There is no automated down-migration against live data (TRD TR-176) | §8 |
| MG-3 | Migrations are **run deliberately**, never automatically by the deploy script (TRD TR-179) | §7 |
| MG-4 | Every migration is **re-runnable-safe**: already applied is a no-op, not an error (TRD TR-177) | — |
| MG-5 | **Expand-then-contract** for breaking changes: add the new structure, deploy code that writes both, backfill, deploy code that reads the new, drop the old in a later migration (TRD TR-178) | DP-4 |
| MG-6 | A migration that moves data is **idempotent and resumable** | MG-4 |
| MG-7 | **Take a backup before running migrations in production** (§12) | — |
| MG-8 | A migration never depends on application classes that may change — it is a historical record |

---

## 7. Deployment sequence

```text
 1. Pre-flight     tests, static analysis, architecture tests green on the commit
 2. Backup         database dump + current release reference (production only)
 3. Fetch          git fetch && git checkout <commit>
 4. Dependencies   composer install --no-dev --optimize-autoloader
 5. Configuration  verify .env has every variable in .env.example
 6. Migrations     php bin/console migration:status  →  php bin/console migrate
 7. Caches         php bin/console cache:clear
 8. Permissions    verify storage/ is writable (§10)
 9. Smoke test     §11
10. Observe        logs and health for a defined window
```

| ID | Rule |
| --- | --- |
| DS-1 | Steps run in this order. Migrations after dependencies, before smoke tests (MG-3) |
| DS-2 | Any step failing **stops the deployment**; it does not continue hopefully (DP-6) |
| DS-3 | Production deployment is **deliberate and recorded** — never automatic on push (TRD TR-186) |
| DS-4 | A deployment is a specific **commit**, not "latest" |
| DS-5 | There is a brief window where new code meets the old schema; MG-5 is what makes it safe |

### 7.1 Development-host automation (existing)

A cron job refreshes the development host from `main` every ten minutes:

```bash
cd /home/arkeonet/public_html/bulbula.arkeonethiopia.com \
  && /usr/local/cpanel/3rdparty/lib/path-bin/git fetch origin main \
  && /usr/local/cpanel/3rdparty/lib/path-bin/git reset --hard origin/main \
  && /opt/cpanel/ea-php84/root/usr/bin/php /home/arkeonet/composer.phar install \
       --no-dev --prefer-dist --no-interaction --optimize-autoloader \
  >> /home/arkeonet/bulbula-deploy.log 2>&1
```

`git reset --hard` **discards anything edited directly on the server**,
including files hand-created in the working tree that are now tracked (an
improvised `public/.htaccess`, for example, is replaced by the committed
one). This is the mechanical enforcement of DP-7.

**Migrations are deliberately not part of this cron** (MG-3).

| ID | Rule |
| --- | --- |
| DA-1 | **This automation is for the development host only.** Production **MUST NOT** auto-deploy from a branch (DS-3, TRD TR-186) |

---

## 8. Rollback

| ID | Rule |
| --- | --- |
| RB-1 | Code rollback is checking out the previous commit and re-running steps 4, 7 and 9 (TRD TR-182) |
| RB-2 | **Schema rollback is not automatic.** Forward-only migrations (MG-2) mean a bad schema change is corrected by a **new** migration |
| RB-3 | MG-5 is what makes RB-1 safe on its own: the old code still works against the expanded schema |
| RB-4 | Restoring a database backup is a **last resort** — it loses data written since the dump — and is a decision, not a reflex |
| RB-5 | The previous release's commit reference is recorded before every production deployment (step 2) |
| RB-6 | A rollback is smoke-tested exactly like a deployment (§11) |

---

## 9. Environments

| | Development host | Production (not yet provisioned) |
| --- | --- | --- |
| Source | `main`, auto-refreshed (§7.1) | A chosen commit, deliberate (DS-3) |
| Debug output | Exception detail in the 500 body | **Opaque message plus correlation id** (TRD TR-144) |
| Log level | Verbose | Reduced |
| Dependencies | `--no-dev` | `--no-dev` |
| Migrations | Manual | Manual, after backup (MG-7) |
| Data | Test data | Real data — **personal data** |

| ID | Rule |
| --- | --- |
| EN-1 | **Production never displays exception detail to a client** (TRD TR-144) |
| EN-2 | **Production data is never copied to a development host.** Real personal data belongs only in production (TRD TR-199) |
| EN-3 | Environment differences are configuration only (DP-5) |
| EN-4 | **Data location and vendor are Open (D-42 / D-42b)** and interact with Proclamation 1321/2024 **Art. 22(1)**, the data-sovereignty provision requiring locally collected personal data to be stored on a server or data centre in Ethiopia (REG-15), and with **Art. 20(1)**, which governs the bases for cross-border transfer (R-26, L-2). Ethiopian colocation and cloud options exist (R-24), so local residency is feasible — but **the choice is the owner's, informed by counsel**, and is not made here. **Whether Art. 22(1) imposes an independent local-storage duty beyond Art. 20 is PENDING COUNSEL (L-2, VT-5.4)** |

---

## 10. Permissions and runtime storage

```text
storage/
├── logs/          application logs
├── cache/         application cache, if filesystem-backed (OT-03)
└── tmp/           transient working files
```

| ID | Rule |
| --- | --- |
| PM-1 | `storage/` is the **only** writable path the application requires (TRD TR-187) |
| PM-2 | `storage/` is **outside the document root** (DR-1) |
| PM-3 | `storage/logs/` **must** be writable by the web-server user, or the application **fails to boot** with `Log directory [...] is not writable.` — a deliberate, loud failure rather than silent logging loss (DP-6) |
| PM-4 | Application code is **not** writable by the web-server user. There is no self-update, no plugin install, no runtime code generation |
| PM-5 | `.env` is readable only by the application user (SC-3) |
| PM-6 | Uploaded media is never executable and is never served from a path that can execute PHP (TRD TR-81) |
| PM-7 | Directories are not world-writable |
| PM-8 | Runtime storage contents are **disposable** — losing `storage/cache/` or `storage/tmp/` loses nothing (TRD TR-212) |

---

## 11. Health checks and smoke test

| Endpoint | Meaning |
| --- | --- |
| `/health`, `/api/v1/health` | **Liveness** — the application booted and can answer. No dependencies |
| `/health/ready`, `/api/v1/health/ready` | **Readiness** — the database is reachable and storage is writable. `503` with per-check detail on failure |

Both are `no-store` (existing behaviour) and neither exposes internal detail
beyond the named check and its status (TRD TR-139).

```bash
curl -si  https://<host>/                              | head -1   # 200, HTML
curl -s   https://<host>/health                                    # {"status":"pass",...}
curl -s   https://<host>/api/v1/health                             # {"status":"pass",...}
curl -s   https://<host>/health/ready                              # pass, or 503 with detail
curl -so /dev/null -w '%{http_code}\n' https://<host>/missing                      # 404
curl -so /dev/null -w '%{http_code}\n' https://<host>/assets/css/styles.css        # 200
```

| ID | Rule |
| --- | --- |
| HK-1 | The smoke test runs after **every** deployment and every rollback (DS-1, RB-6) |
| HK-2 | A failing smoke test triggers rollback (§8), not investigation on a live site |
| HK-3 | Readiness is monitored continuously once production exists (TRD TR-138) |

---

## 12. Backups

| ID | Rule |
| --- | --- |
| BK-1 | The database is backed up on a defined schedule. **It is the only irreplaceable thing** (TRD TR-210) |
| BK-2 | Uploaded media is backed up — originals cannot be regenerated (TRD TR-211, IM-5) |
| BK-3 | `vendor/`, caches, the search document and logs are **not** backed up as recovery material; all are rebuildable (TRD TR-212) |
| BK-4 | **A backup is not a backup until a restore has been tested** (TRD TR-213) |
| BK-5 | Backups contain personal data and are therefore protected, access-controlled, and subject to the retention schedule — **PENDING COUNSEL** (L-21, TRD TR-214) |
| BK-6 | Backup **location is bound by the data-sovereignty requirement** of Proclamation 1321/2024 **Art. 22(1)** (REG-15, R-26): a backup stored abroad is also a cross-border transfer and must therefore satisfy **Art. 20(1)** (VT §4, SO-8.7). **PENDING COUNSEL / Open (D-42, L-2, L-10)** |
| BK-7 | Frequency, retention depth and restore-time objectives are **`Open — technical decision`** pending D-20 and D-42 |
| BK-8 | A backup is taken immediately before any production migration (MG-7) |

---

## 13. Scheduled work

All background work is **cron-driven console commands** (TRD TD-06). **No
broker, no daemon, no worker process** (NG-8).

| Command | Purpose |
| --- | --- |
| `queue:work` | Drain the queued-job table (email, derivative generation) |
| `search:reindex` | Rebuild search documents; safe to re-run |
| `analytics:rollup` | Aggregate events into rollups |
| `listings:freshness` | Flag Listings due for re-verification (D-08) |
| `maintenance:prune` | Expire OTP state, sessions, cache entries, temporary files |

| ID | Rule |
| --- | --- |
| CR-1 | Every command is **idempotent** and safe to re-run (TRD TR-152) |
| CR-2 | Every command is **bounded** in batch size and runtime, so a run cannot overlap its successor |
| CR-3 | Overlap is prevented by a lock; a second invocation exits cleanly rather than racing |
| CR-4 | Failures are logged with context and exit with a non-zero status (TRD TR-153) |
| CR-5 | **No cron job is required for the site to serve correct pages.** Cron absence degrades freshness, never correctness (TRD TR-154) |
| CR-6 | `bin/console` is the **only** file permitted to call `exit()` (ArchTest) |
| CR-7 | Cron invokes the **PHP 8.4 binary explicitly** (§2) |
| CR-8 | Cron output goes to a log file, never to email |

---

## 14. Logging

| ID | Rule |
| --- | --- |
| LG-1 | Logs are written through Monolog to `storage/logs/`, configured by `config/logging.php` |
| LG-2 | Every entry carries the **correlation id** (TRD TR-12) |
| LG-3 | **No personal data, no credential, no token, no OTP code, no session identifier in logs** (TRD TR-25, SC-4) |
| LG-4 | Logs are rotated and pruned; unbounded growth fills a shared-hosting quota and takes the site down |
| LG-5 | Logs are **outside the document root** (DR-1) and never publicly readable |
| LG-6 | A boot-time failure is written to the server's PHP error log, since the application logger may not exist yet |
| LG-7 | Log retention is bounded — **PENDING COUNSEL** (L-21) |

---

## 15. Production safety

| ID | Rule |
| --- | --- |
| PS-1 | No debug output, no stack trace, no SQL, no file path in any production response (EN-1) |
| PS-2 | No development tooling installed in production (IN-1) |
| PS-3 | No `dd`, `dump`, `var_dump`, `die`, `exit` or `sleep` anywhere in the codebase — **enforced by `tests/Arch/ArchTest.php`** |
| PS-4 | No database client, no admin panel, no shell exposed over HTTP from this application |
| PS-5 | HTTPS everywhere; HTTP redirects to HTTPS |
| PS-6 | Security headers are set at the application layer so they survive a host change (TRD §34) |
| PS-7 | **No manual edits on a production server** (DP-7) |
| PS-8 | **Production data is never copied to a development host** (EN-2) |
| PS-9 | Staff console paths are not indexable and not linked publicly |
| PS-10 | Only an explicitly authorised person deploys to production; the deployment is recorded (DS-3) |

---

## 16. Troubleshooting

Preserved from the Phase 1 operational record.

### Every URL answers 500, static assets included

Apache rejected a directive in `public/.htaccess` **before PHP ran**. The
giveaway is that a plain `.css` file 500s too, with an empty body. The error
log shows `... /public/.htaccess: <directive> not allowed here`. See the
`.htaccess` warning in §3.1 — keep the file to `DirectoryIndex` and the
`mod_rewrite` block.

### Every PHP URL answers 500 while static files are fine

The request reached an interpreter older than PHP 8.4, which cannot parse
this codebase. The cPanel MultiPHP handler block was lost when the deploy
reset the working tree. See §3.1 — the block belongs in the committed
`public/.htaccess`.

### The home page 500s but `/health` works

`/health` has no dependencies; `/` reads `resources/views/home.html`. Check
that the deploy created `resources/` and that the file is readable.

### A 500 with an empty body

This can no longer come from the application: the front controller catches
anything thrown while booting, writes it to the server's PHP error log, and
answers with a readable `500 Internal Server Error` body — naming the
exception, file and line outside production, and staying opaque in
production. An empty 500 therefore means **the request never reached PHP**
— look at the web-server configuration (first entry above).

### Booting fails with an unwritable log directory

`storage/logs/` must be writable by the web-server user (PM-3). The failure
is deliberate and loud rather than silently dropping log lines.

### `/health/ready` answers 503

The database settings in `.env` are wrong or MariaDB is unreachable. The
response body names the failing check.

---

## 17. Not in V1

| Not used | Why | Reference |
| --- | --- | --- |
| Docker, Kubernetes | Shared hosting has no container runtime | TRD NG-10 |
| CI-triggered production deployment | Production deployment is deliberate | DS-3 |
| Blue/green, canary releases | Needs infrastructure V1 does not have | — |
| Configuration-management tooling | One host | — |
| A process supervisor or daemon | Cron only | TD-06 |
| An external secret manager | `.env` with restricted permissions | SC-2, D-23 |
| A CDN | First-party assets | `performance-and-caching.md` §9 |
| Multiple application servers | Single host at launch | — |

---

## 18. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-20 | Host limits and MariaDB tuning | Open — bounds backup and cron budgets |
| D-21 | Maps key and billing | Open — a secret in §5.2 |
| D-23 | **Production configuration and secret custody** | **Open — the central deployment decision** |
| D-25 | Media storage | Open — affects BK-2 |
| D-33 | Telegram bot token custody | Open |
| D-41 | Email provider and credential | Open |
| D-42 / D-42b | **Data location and vendor** | **Open — residency, R-26 / L-2** |
| L-2, L-10 | Residency and cross-border transfer | **PENDING COUNSEL** |
| L-21 | Retention of backups and logs | **PENDING COUNSEL** |
| TRD OT-03 | Filesystem versus database cache backend | Open — affects `storage/cache/` |
| — | Production host, domain and provisioning date | Not yet decided |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, Art. 22(1) | **Data sovereignty** — locally collected personal data stored on a server or data centre in Ethiopia; binds hosting (EN-4) and backup location (BK-6) | Confirmed — statute/regulation |
| Art. 20(1) | The four bases for cross-border transfer; engaged whenever hosting or backups sit outside Ethiopia | Confirmed — statute/regulation |
| Whether Art. 22(1) imposes an independent local-storage duty beyond Art. 20 | Decides whether a foreign host is available at all (D-42, D-42b) | **PENDING COUNSEL (L-2, VT-5.4)** |
| Retention of backups and logs | BK-5, BK-7 | **PENDING COUNSEL (L-21)** |

The article map and the full regulatory assessment live in
[`../55-privacy/privacy-governance-v1.0.md`](../55-privacy/privacy-governance-v1.0.md)
§11 (REG-13, REG-15) and
[`../55-privacy/vendor-and-transfer-register-v1.0.md`](../55-privacy/vendor-and-transfer-register-v1.0.md)
§4–§5. This document cites them; it does not interpret them.

---

## Decision references

D-08, D-20, D-21, D-23, D-25, D-33, D-41, D-42, D-42b, D-48.
