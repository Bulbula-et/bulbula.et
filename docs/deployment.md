# Deployment

The repository is the source of truth. Nothing that a deployed environment
needs may exist only on a server.

## Document root

The web server must serve **`public/`**, never the repository root.

```
bulbula.arkeonethiopia.com/     <- repository root, NOT web accessible
├── bin/ config/ database/ docs/ resources/ routes/ src/ tests/ vendor/
├── composer.json composer.lock .env
└── public/                     <- document root
    ├── .htaccess
    ├── index.php               <- the only entry point
    └── assets/
```

Everything outside `public/` — `.env`, `composer.json`, `composer.lock`,
`src/`, `config/`, `database/`, `resources/`, `tests/`, `vendor/` — stays
outside the document root. The HTML of the pre-launch page lives in
`resources/views/home.html` and is rendered by the application, so there is no
second entry point for a web server to discover.

If the document root cannot be changed from the repository root, fix the
vhost; do not work around it with rewrites that expose the project files.

## Front-controller rules

`public/.htaccess` is part of the repository:

```apache
DirectoryIndex index.php
Options -Indexes

<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

- `/` → `index.php` (the directory index is the front controller)
- `/health`, `/api/v1/health` → `index.php` with the original URI
- `/assets/css/styles.css` → served by Apache, never routed

The rewrite is internal, so `REQUEST_URI` keeps the path the client asked
for. That is what the router matches on.

> **Do not reintroduce a static `index.html` in `public/`.** Apache resolves a
> directory index before the rewrite runs, so a static file there silently
> takes over `/` — and a rewrite pointing at `/public/index.html` makes the
> application receive that literal path and answer `404 No route matches
> [/public/index.html]`.

## cPanel server notes (development: bulbula.arkeonethiopia.com)

The default shell `php` on that host is 8.2; the application requires 8.4.
Always call the 8.4 binary explicitly:

| Purpose | Path |
| --- | --- |
| PHP 8.4 CLI | `/opt/cpanel/ea-php84/root/usr/bin/php` |
| Composer | `/home/arkeonet/composer.phar` |
| Git | `/usr/local/cpanel/3rdparty/lib/path-bin/git` |

Install dependencies without dev tooling:

```bash
/opt/cpanel/ea-php84/root/usr/bin/php /home/arkeonet/composer.phar install \
    --no-dev --prefer-dist --no-interaction --optimize-autoloader
```

`vendor/` is generated on the server and is never committed. `.env` is
created once on the server from `.env.example` and is never committed.

`storage/logs/` must be writable by the user the web server runs as, or the
application fails to boot with `Log directory [...] is not writable.` — that
is a deliberate, loud failure rather than silent logging loss.

## Automated development deployment

A cron job refreshes the development host from `main` every ten minutes:

```bash
cd /home/arkeonet/public_html/bulbula.arkeonethiopia.com \
  && /usr/local/cpanel/3rdparty/lib/path-bin/git fetch origin main \
  && /usr/local/cpanel/3rdparty/lib/path-bin/git reset --hard origin/main \
  && /opt/cpanel/ea-php84/root/usr/bin/php /home/arkeonet/composer.phar install \
       --no-dev --prefer-dist --no-interaction --optimize-autoloader \
  >> /home/arkeonet/bulbula-deploy.log 2>&1
```

`git reset --hard` discards anything edited directly on the server, including
files that were hand-created in the working tree and are now tracked (an
improvised `public/.htaccess`, for example, is replaced by the committed one).
Never patch the server by hand: change the repository and let the cron deploy.

Database migrations are not part of the cron. Run them deliberately:

```bash
/opt/cpanel/ea-php84/root/usr/bin/php bin/console migration:status
/opt/cpanel/ea-php84/root/usr/bin/php bin/console migrate
```

## Smoke test after a deploy

```bash
curl -si  https://bulbula.arkeonethiopia.com/            | head -1   # 200, HTML
curl -s   https://bulbula.arkeonethiopia.com/health                  # {"status":"pass",...}
curl -s   https://bulbula.arkeonethiopia.com/api/v1/health           # {"status":"pass",...}
curl -so /dev/null -w '%{http_code}\n' https://bulbula.arkeonethiopia.com/missing          # 404
curl -so /dev/null -w '%{http_code}\n' https://bulbula.arkeonethiopia.com/assets/css/styles.css  # 200
```

`/health/ready` additionally requires the database settings in `.env`; it
answers `503` with a per-check detail when MariaDB is unreachable.

Nothing in this document is a production procedure: the development host is
the only deployed environment so far.
