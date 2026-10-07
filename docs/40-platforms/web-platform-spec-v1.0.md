# Web Platform Specification

| | |
| --- | --- |
| **Document** | Web Platform Specification — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The Web surface: how it is delivered, what it must do without
JavaScript, how sessions and URLs behave, what it owes to search engines,
and the device and network envelope it must survive.

**Scope.** Delivery and surface behaviour. Screens are in
[`../20-ux-ui/public-web-ux-v1.0.md`](../20-ux-ui/public-web-ux-v1.0.md);
components in
[`../20-ux-ui/component-spec-v1.0.md`](../20-ux-ui/component-spec-v1.0.md);
the shared contract in
[`platform-strategy-v1.0.md`](platform-strategy-v1.0.md).

**This document selects no technology.** D-16 (JavaScript approach) and
D-17 (view layer) remain open.

---

## 1. What the Web surface is

| ID | Statement | Source |
| --- | --- | --- |
| WEB-1.1 | The Web is a **first-class V1 surface**, not the fallback for the Mini App | SUR-7 |
| WEB-1.2 | It is **mobile-first**: the small touch screen is the design baseline, larger viewports are supported but are not the baseline | D-52, MOB-1, MOB-6 |
| WEB-1.3 | It is the **only SEO surface** | C-37, TG-7 |
| WEB-1.4 | It **hosts the operations console** at `/ops/*` | `scope-v1.md` §1.5 |
| WEB-1.5 | It is served from **one domain**, with no separate mobile domain and no `/m/` path | IAR §11 |
| WEB-1.6 | **HTTPS everywhere**; HTTP redirects to HTTPS | PS-5 |

---

## 2. Delivery model

| ID | Rule | Source |
| --- | --- | --- |
| WEB-2.1 | Pages are **server-rendered HTML**. The browser receives content, not instructions to fetch content | MOB-5, SEO-1 |
| WEB-2.2 | **The Web surface never calls its own HTTP API.** Web controllers invoke application services in process | **TD-01**, `trd-v1.0.md` §19 |
| WEB-2.3 | Web controllers and API controllers are **two thin adapters over the same application services** | `trd-v1.0.md` §19 |
| WEB-2.4 | **No single-page application.** An SPA was rejected | D-16 |
| WEB-2.5 | **No client-side router**, no hydration step, and no build-time pipeline the shared host cannot run | DSN-12.7, `deployment.md` |
| WEB-2.6 | The JavaScript approach — htmx plus Alpine, or vanilla modules — is **Open (D-16)**. Nothing in this specification depends on which is chosen | D-16 |
| WEB-2.7 | The view layer is **Open (D-17)** | D-17 |

---

## 3. Progressive enhancement — the hard floor

This is the single most consequential rule for the Web surface, because it
is what keeps the product usable on the devices and networks it is actually
for (R-06).

| ID | Rule | Source |
| --- | --- | --- |
| WEB-3.1 | **Core content retrieval must work without JavaScript** | MOB-5, C-37 |
| WEB-3.2 | Every navigational control is a **real link or a real form** | IAR-34 |
| WEB-3.3 | **Search works without JavaScript.** The field is a form; submitting it loads a results page | CMP-0.9 |
| WEB-3.4 | Browsing Categories, Areas, Category × Area and Business profiles works without JavaScript | WEB-3.1 |
| WEB-3.5 | Pagination is **real links with real URLs** | CMP §27 |
| WEB-3.6 | The product must also work **without a map loading** and **without device location** | MOB-5 |
| WEB-3.7 | JavaScript **enhances**; it never gates. Autocomplete, filter sheets, deferred maps, galleries and the Save control are enhancements over working baselines | CMP-0.9 |
| WEB-3.8 | A failed or blocked script must leave a **working page**, not a broken one | WEB-3.1 |

### 3.1 Enhancement inventory

| Feature | Baseline without JavaScript | Enhancement with JavaScript |
| --- | --- | --- |
| Search | Form submit → results page | Autocomplete suggestions (C-03) |
| Filters | Form with an explicit apply, or links | Bottom sheet, live count |
| Sort | Form or links | In-place update |
| Save | Form post → redirect back to the same place | In-place toggle with announcement |
| Map | Address, landmark and a directions link | Deferred interactive embed (**D-21 open**) |
| Gallery | Images in sequence, each reachable | Swipe plus a full-screen viewer |
| Hours | Full schedule rendered | Expand and collapse |
| Report form | Its own page | Bottom sheet or modal |
| Share | Copy-link control | Platform share sheet |

| ID | Rule |
| --- | --- |
| WEB-3.9 | Every row above must be **demonstrated working in its baseline column** before the enhancement is accepted |
| WEB-3.10 | An enhancement may not change what the action does — only how it feels |

---

## 4. URLs, routing and history

| ID | Rule | Source |
| --- | --- | --- |
| WEB-4.1 | The URL grammar is fixed by the information architecture and is not re-decided here | IAR §2 |
| WEB-4.2 | Every public page has a **single canonical URL** | SEO-2, IAR-2 |
| WEB-4.3 | A Business profile URL **survives a name change**; a changed slug redirects permanently | IAR-3, SEO-4 |
| WEB-4.4 | Filter, sort and pagination parameters **do not create second indexable URLs** | SEO-10, IAR-5 |
| WEB-4.5 | **Back restores the previous page with its state** — same results, filters and scroll position | IAR-28 |
| WEB-4.6 | Filter, sort and pagination changes each produce a **real history entry** | IAR-29 |
| WEB-4.7 | **No infinite scroll** | IAR-30 |
| WEB-4.8 | Opening a modal or sheet pushes a history entry; Back closes it | IAR-31 |
| WEB-4.9 | Sign-in **replaces** rather than stacks in history | IAR-33 |
| WEB-4.10 | A removed Listing returns **not found** and leaves the sitemap | SEO-13, IAR-8 |

---

## 5. Sessions and state

| ID | Rule | Source |
| --- | --- | --- |
| WEB-5.1 | The Web transports its session as a **cookie**; the Mini App and API use a bearer token. **Both resolve to the same server-side session record** | TD-02, S-1 |
| WEB-5.2 | There is **one session concept**, not two session systems | TD-02 |
| WEB-5.3 | A Customer may hold concurrent sessions across devices and surfaces | S-8 |
| WEB-5.4 | Session lifetime, idle timeout and rotation values are **Open — technical decision (OT-01)** | OT-01 |
| WEB-5.5 | Session expiry **preserves in-progress input** and restores it after re-authentication | A11-14.3, WCAG 2.2.5 |
| WEB-5.6 | State-changing requests carry CSRF protection | `trd-v1.0.md` §19 route pipeline |
| WEB-5.7 | **Guest browsing requires no session and sets no identifying cookie** | GS-1, TR-202 |
| WEB-5.8 | Whether any banner is required for cookies is **PENDING COUNSEL** (L-5, L-6) and is not assumed here | CMP §46 |

---

## 6. SEO delivery obligations

The Web surface is the only one with these obligations (C-37). The rules
are set in the UX documents; this section states what **delivery** must
provide for them to hold.

| ID | Rule | Source |
| --- | --- | --- |
| WEB-6.1 | Semantic HTML: real headings, lists, landmarks, links and forms | SEO-1, WCAG 1.3.1 |
| WEB-6.2 | Content is present in the **initial HTML response**, not assembled client-side | SEO-1, WEB-2.1 |
| WEB-6.3 | One `h1` per page; heading levels do not skip | IAR-49 |
| WEB-6.4 | Unique title and description per indexable page | SEO-5, CDN-14.1 |
| WEB-6.5 | Canonical link on every public page | SEO-2 |
| WEB-6.6 | Structured data for Business, location, hours and rating **where one exists**, matching what is visible | SEO-6, R-15 |
| WEB-6.7 | Breadcrumb structured data | SEO-12 |
| WEB-6.8 | A sitemap listing indexable pages, kept current as Listings are published and unpublished | SEO-7, SEO-13 |
| WEB-6.9 | `noindex` on `/search`, `/nearby`, `/signin`, `/account/*`, `/saved`, `/ops/*` and the report and review forms | SEO-8, IAR-46 |
| WEB-6.10 | A Category × Area page below the minimum-content rule is **served but not indexed** and is excluded from the sitemap. The threshold itself is **Open — product detail** | SEO-9, PWX §7 |
| WEB-6.11 | Link previews: title, description and image metadata on every public page | SEO-11, C-17 |
| WEB-6.12 | The URL model must not preclude Amharic pages later | SEO-14, IAR-6 |

**Out of scope here.** This is not the technical SEO implementation. Robots
directives, sitemap generation cadence, structured-data vocabulary
selection and redirect implementation belong to the build.

---

## 7. Performance envelope

Shared hosting, one server, **no cache service** (TD-05), **no background
worker** (TD-06), no CDN assumed (`deployment.md`).

| ID | Budget / rule | Source |
| --- | --- | --- |
| WEB-7.1 | Core Web Vitals at p75: **LCP ≤ 2.5 s · INP ≤ 200 ms · CLS ≤ 0.1** — all `[P]` | NFR-P |
| WEB-7.2 | **≤ 150 KB critical HTML and CSS; ≤ 100 KB JavaScript** `[P]`. A design that will not fit is redesigned, not re-budgeted | DSN-12.1 |
| WEB-7.3 | **Zero font requests for Latin text.** The Ethiopic face is a conditional, `unicode-range`-scoped enhancement | DSN-3.2, DSN-3.4 |
| WEB-7.4 | No CSS framework and no component library | DSN-12.3 |
| WEB-7.5 | Images: responsive sources, lazy below the fold, reserved space so nothing shifts | DSN-8.12…8.15, MOB-4 |
| WEB-7.6 | Third-party embeds are deferred and **never render-blocking** | DSN-12.6 |
| WEB-7.7 | Every list is paginated with a bounded page size; no page loads an unbounded set | OPX-16.1 |
| WEB-7.8 | **Data usage is a cost the User pays**: no large payload for a small outcome | MOB-4, UXP-8 |
| WEB-7.9 | The homepage is the LCP test case and carries no above-the-fold media | PWX §1 |
| WEB-7.10 | Console load must not degrade public performance | OPX-16.5 |
| WEB-7.11 | Confirmed host resource limits and MariaDB tuning are **Open — technical decision (D-20)** | D-20 |

---

## 8. Device and network envelope

| ID | Requirement | Source |
| --- | --- | --- |
| WEB-8.1 | Usable on **mid- and low-range Android devices over constrained networks** | MOB-3, R-06 |
| WEB-8.2 | Primary actions reachable **with one hand** on a typical phone | MOB-2, UXP-2.1 |
| WEB-8.3 | No horizontal scrolling at a 320 px equivalent width | A11-15.2, WCAG 1.4.10 |
| WEB-8.4 | Usable at 200 % zoom with no loss of content or function | A11-15.1 |
| WEB-8.5 | Both orientations supported; orientation never locked | A11-15.6 |
| WEB-8.6 | Expensive visual effects — large blurs, many shadows, heavy compositing — are treated as **design** problems on low-end GPUs | DSN-12.5 |
| WEB-8.7 | Browser support follows **capability, not version lists**: the baseline experience works everywhere that renders HTML and CSS; enhancements apply where supported | WEB-3.7 |
| WEB-8.8 | A feature that cannot degrade gracefully is not added | WEB-3.8 |

---

## 9. Security surface

Set by the TRD; restated here only as it bears on Web delivery.

| ID | Rule | Source |
| --- | --- | --- |
| WEB-9.1 | Secure headers are applied by the existing middleware: CSP, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy` | `architecture.md`, existing foundation |
| WEB-9.2 | HSTS is added only over HTTPS | `architecture.md` |
| WEB-9.3 | The Mini App requires a `frame-ancestors` policy admitting the Telegram host. **It must not weaken the Web policy** — **Open — technical decision (OT-05)** | OT-05, TRD §34 |
| WEB-9.4 | Uploaded files are not stored in the document root and are not executable | TR-81 |
| WEB-9.5 | Error output is safe: no internal identifier, stack trace or SQL reaches a User | TR-144, UFL-0.7 |
| WEB-9.6 | `/ops/*` is never linked from a public page, never mentioned in public navigation and never indexed | OPX-0.5 |
| WEB-9.7 | Staff authentication is separate from Customer authentication; its strength is **Open (D-45)** | ST-2, D-45 |
| WEB-9.8 | A permission failure does not disclose whether the target exists | ENF-2, TR-35 |

---

## 10. The operations console on this surface

| ID | Rule | Source |
| --- | --- | --- |
| WEB-10.1 | The console is Web-only and lives at `/ops/*` | IAR-41 |
| WEB-10.2 | It is desktop-first in layout but **meets the same WCAG 2.2 AA bar**, and hides no data at smaller widths | OPX-0.9, DSN-8.4 |
| WEB-10.3 | It shares the same token set, components and content rules as the public surface | PLT-7 |
| WEB-10.4 | Its behaviour is specified in [`../20-ux-ui/operations-console-ux-v1.0.md`](../20-ux-ui/operations-console-ux-v1.0.md) | — |

---

## 11. Verification

| ID | Check |
| --- | --- |
| WEB-11.1 | Every row of the §3.1 enhancement inventory works with JavaScript disabled |
| WEB-11.2 | Search, browse and profile pages render complete content in the initial HTML response |
| WEB-11.3 | Back restores results, filters and scroll position after navigation, filtering and pagination |
| WEB-11.4 | Indexable pages carry unique titles, descriptions and canonicals; non-indexable pages carry `noindex` |
| WEB-11.5 | Structured-data ratings equal the visible ratings |
| WEB-11.6 | Budgets in §7.2 are measured on the real homepage, search and profile pages |
| WEB-11.7 | Measured on a mid-range Android device over a constrained network, not only in a desktop emulator |
| WEB-11.8 | 320 px width and 200 % zoom produce no horizontal scrolling and no loss of function |
| WEB-11.9 | No Web page calls the public HTTP API (TD-01) |
| WEB-11.10 | `/ops/*` is absent from the sitemap and returns no information to an unauthenticated request |

---

## 12. Open items

| ID | Item | Status |
| --- | --- | --- |
| D-16 | Frontend JavaScript approach (htmx + Alpine vs vanilla; SPA rejected) | **Open — implementation detail** |
| D-17 | View layer | **Open — implementation detail** |
| D-20 | Host resource limits and MariaDB tuning | **Open — technical decision** |
| D-21 | Maps provider, embed strategy and fallback | **Open — product detail** |
| D-25 | Media storage, sizes and limits | **Open — implementation detail** |
| D-45 | Staff authentication strength | **Open — implementation detail** |
| OT-01 | Session lifetime, idle timeout, rotation | **Open — technical decision** |
| OT-05 | CSP `frame-ancestors` admitting the Telegram host | **Open — technical decision** |
| OT-08 | Pagination style for public lists | **Open — technical decision** |
| — | Minimum-content threshold for SEO-9 | **Open — product detail** |
| — | Production host, domain and provisioning date | **Open — product detail**; not decided (`deployment.md`) |
| L-5 / L-6 | Whether a cookie banner is required | **PENDING COUNSEL** |

---

## Decision references

D-16, D-17, D-20, D-21, D-25, D-45, D-52.
