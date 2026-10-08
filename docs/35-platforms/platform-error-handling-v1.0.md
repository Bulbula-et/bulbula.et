# Platform Error Handling

| | |
| --- | --- |
| **Document** | Platform Error Handling — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** How failure behaves on each surface: the taxonomy, where a
failure is shown, what it says, what it never says, and how the User
recovers.

**Scope note.** The API error contract — statuses, envelope and rules
E-1…E-6 — is settled in `docs/30-technical/api-spec-v1.0.md` and is
**observed, not redefined, here**. Error copy is governed by
`docs/20-ux-ui/content-design-v1.0.md`.

---

## 1. Principles

| ID | Principle | Source |
| --- | --- | --- |
| PEH-1.1 | **A failure is always reported.** Silence is the worst outcome | CDN §6 |
| PEH-1.2 | **The User is never blamed** | CDN §6 |
| PEH-1.3 | **Every error says what happened, why if known, and what to do next** | CDN §6 |
| PEH-1.4 | **No error discards the User's input** | UFL §9 |
| PEH-1.5 | **No error is a dead end.** There is always a route onward | IAR-37 |
| PEH-1.6 | **Nothing internal leaks** — no stack trace, SQL, file path, internal identifier or class name | E-2, TR-144 |
| PEH-1.7 | **Error behaviour is identical on both surfaces**; only chrome and placement differ | SCC-2.4 |
| PEH-1.8 | **An uncertain outcome is stated as uncertain**, never resolved optimistically | PEH-4.1 |

---

## 2. Honesty rules

| ID | Rule | Source |
| --- | --- | --- |
| PEH-2.1 | **No failure is silent.** Any action that does not complete says so | PEH-1.1 |
| PEH-2.2 | **No error is disguised as success.** A server error returns a server error status — never a 200 with an apology | PSE-3.9 |
| PEH-2.3 | **No success state appears for an action the server has not accepted** | PP-8.1 |
| PEH-2.4 | **An optimistic interface state must be reversed visibly** if the server rejects it, with an explanation — not reverted in silence | — |
| PEH-2.5 | **Degraded data is labelled as degraded.** Stale hours are shown with their known-as-of date, not as current fact | UFL-B4 |
| PEH-2.6 | **A partial result is labelled partial.** Results that are incomplete because something failed never present as complete | PEH-6.4 |
| PEH-2.7 | **No error page is indexable, and none returns a success status** | PSE-2.5 |
| PEH-2.8 | **No fabricated reassurance** — no "we're on it" unless someone genuinely is | CDN §6 |

---

## 3. Taxonomy

| Class | Example | Where shown | Recovery |
| --- | --- | --- | --- |
| **Input** | Required field missing, malformed email | **Inline, at the field** | Fix in place; input retained |
| **Action** | Save failed, Review submission failed | **At the action** | Retry the action |
| **Permission** | Signed out, lacks permission | **At the action** | Authenticate, or explain |
| **Not found** | Business, category or area does not exist | **Page level** | Nearest valid ancestor plus search |
| **Gone** | Listing unpublished or removed | **Page level** | Honest statement plus onward routes |
| **Transport** | Network lost, request timed out | **At the action, or page level** | User-initiated retry |
| **Server** | Unexpected failure | **Page level** | Apology, correlation reference, onward route |
| **Unavailable** | Planned or temporary outage | **Page level** | Statement plus retry later |
| **Rate limited** | Too many attempts | **At the action** | Wait; **the limit is not disclosed** |
| **Host** | Mini App launch context invalid | **Nowhere** | Full Guest experience (TM-7.14) |

| ID | Rule |
| --- | --- |
| PEH-3.1 | **An error appears at the level of what failed.** A failed Save does not replace the Business profile with an error page |
| PEH-3.2 | **A page-level error never discards a page that still works.** If content rendered, it stays rendered (PP §8) |
| PEH-3.3 | **An error message never pushes content the User is reading** — it reserves or overlays (PP-4.8) |
| PEH-3.4 | **Only one error is shown per failure.** No toast plus banner plus inline message for one event |
| PEH-3.5 | **A slow response is reported as slow, not as broken**; the wait is acknowledged before it is called a failure |

---

## 4. Writes

Applies to Save (C-14), Review submission (C-13), Report submission
(C-15) and every Operations write.

| ID | Rule | Source |
| --- | --- | --- |
| PEH-4.1 | **A write is successful only when the server says so.** Nothing else counts | PP-8.1 |
| PEH-4.2 | **Input is preserved on failure.** A written review survives a failed submission | UFL §9 |
| PEH-4.3 | **Retry is user-initiated**, retries the action rather than the page, and is not automatic (PP-5.7) | PP-5.7 |
| PEH-4.4 | **No write is queued for later replay** — there is no offline storage, so there is nowhere honest to queue it | PD-12 |
| PEH-4.5 | **Where the outcome is genuinely unknown, the interface says so** and tells the User how to check, rather than guessing | PEH-1.8 |
| PEH-4.6 | **Report submission and campaign creation are idempotent** by key, so a retry cannot create a duplicate | TR-159 |
| PEH-4.7 | **Save and Review are naturally unique**, so a repeated attempt cannot duplicate | TR-160 |
| PEH-4.8 | **An OTP is single-use**; a retry after consumption is reported clearly and resend is offered | TR-163, PAU-4.7 |
| PEH-4.9 | **A duplicate-state response is not an error to the User.** Saving something already saved shows it saved | — |
| PEH-4.10 | **A conflict is explained in product terms**, not as a protocol condition | CDN §6 |

---

## 5. Authentication and session

| Situation | Behaviour |
| --- | --- |
| Not signed in, action needs it | Reason stated; **intent preserved**; authenticate; action completes (UFL-0.1) |
| Session expired mid-task | **Intent preserved**; re-authenticate; action completes (PN-7.6) |
| Signed in, lacks permission | Explained plainly; **no internal permission string shown** (TD-03) |
| Existence is privileged | **Absence is returned rather than refusal** (TR-35) |
| Credential failure | **Never discloses whether an account exists** (E-5, C-31) |
| Rate limited | Wait stated; **the limit is never disclosed** (E-6) |
| Launch context invalid | **Full Guest experience** — no error (TM-7.14) |
| Google return lost | Mini App usable; email OTP offered (PAU-5.5) |

| ID | Rule |
| --- | --- |
| PEH-5.1 | **An expired session never means lost work.** The attempted action survives re-authentication |
| PEH-5.2 | **"You must sign in" is never shown without saying why that action needs it** (CDN §4) |
| PEH-5.3 | **No authentication error reveals account existence**, through message, status or timing |
| PEH-5.4 | **No internal permission identifier ever appears in an interface or an error** (E-2) |

---

## 6. Reads and partial failure

| Failure | Behaviour |
| --- | --- |
| Map fails (C-10) | Address, Area and landmark remain as text; map area states it could not load (MOB-5) |
| Images fail | Alternative text and reserved layout hold; no broken frames |
| Reviews block fails (C-13) | Profile remains complete; the block states it could not load and offers retry |
| Autocomplete fails (C-03) | Silent — it is an enhancement; **full search still submits** (PWX-2) |
| Geolocation denied or fails (C-07) | **Not an error.** Area selection is offered instead (UFL-N3) |
| Search backend fails (C-02) | Honest failure plus category and area browsing as alternatives |
| Sponsored block fails (C-16) | **Omitted entirely.** Never rendered unlabelled, never substituted with an organic result presented as sponsored, or the reverse (LB-4) |
| A result page is empty | **Not an error.** Honest emptiness plus onward routes (UFL §7) |

| ID | Rule |
| --- | --- |
| PEH-6.1 | **One failed block never takes down a page.** Everything that rendered stays |
| PEH-6.2 | **An enhancement failing is not an error** and is not reported as one (PWX-1) |
| PEH-6.3 | **A permission refusal is not an error** — it is a choice, and an alternative route is offered |
| PEH-6.4 | **Partial results are labelled partial** (PEH-2.6) |
| PEH-6.5 | **No empty state is padded** to look populated (PSE-8.3) |

---

## 7. Not found, gone and stale links

| ID | Rule | Source |
| --- | --- | --- |
| PEH-7.1 | **An unknown URL resolves to the nearest valid ancestor with an explanation plus search** — never a blank page, never a silent redirect to Home | PN-5.6 |
| PEH-7.2 | **Removed content returns a gone status, not a redirect to Home.** A redirect tells the User nothing and tells a crawler something false | PSE-3.8 |
| PEH-7.3 | **A renamed slug redirects** to its canonical URL; shared links must not rot | PSE-4.3 |
| PEH-7.4 | **An unpublished or suspended listing states that it is not currently available**, without disclosing internal moderation state or reasons | `listing-operations.md` |
| PEH-7.5 | **No moderation reason, operator note or internal status is ever exposed** | E-2 |
| PEH-7.6 | **A removed review's absence is not explained on the public profile**; policy is explained on `review-policy` | `review-policy.md` |
| PEH-7.7 | **A stale deep link behaves identically on both surfaces** | SCC-2.4 |
| PEH-7.8 | **No error page carries a success status, and none is indexable** | PEH-2.7 |

---

## 8. What is never shown

| ID | Never exposed | Source |
| --- | --- | --- |
| PEH-8.1 | Stack traces, exception classes, file paths, line numbers | E-2, TR-144 |
| PEH-8.2 | SQL, query fragments, schema or table names | E-2 |
| PEH-8.3 | Internal identifiers, primary keys, queue names, host names | E-2 |
| PEH-8.4 | Internal permission strings or role names | TD-03 |
| PEH-8.5 | Whether an email address has an account | E-5, C-31 |
| PEH-8.6 | Rate-limit values, thresholds or remaining quota | E-6 |
| PEH-8.7 | Moderation decisions, operator notes, internal listing state | PEH-7.5 |
| PEH-8.8 | Third-party vendor names or vendor error text | — |
| PEH-8.9 | Any personal data belonging to another person | TR-201 |
| PEH-8.10 | Anything that makes the existence of privileged content inferable | TR-35 |

| ID | Rule |
| --- | --- |
| PEH-8.11 | **Unexpected server failures are opaque to the User and carry a correlation reference** the User can quote (E-3, TR-12) |
| PEH-8.12 | **The correlation reference is meaningless on its own** and reveals nothing about the system |
| PEH-8.13 | **The full detail exists in logs**, never in the response (E-3) |

---

## 9. Client behaviour

| ID | Rule | Source |
| --- | --- | --- |
| PEH-9.1 | **Clients branch on the stable error code, never on the message** | E-1, PD-11 |
| PEH-9.2 | **Messages are presentation and may change without being a breaking change** | E-1 |
| PEH-9.3 | **Validation detail is used only for validation errors**, mapped to the fields it names | E-4 |
| PEH-9.4 | **An unrecognised code falls back to a safe generic message** — never a raw code, never a blank screen | PEH-1.6 |
| PEH-9.5 | **Raw server text is never rendered unmodified** into the interface | PEH-8.1 |
| PEH-9.6 | **The Web surface does not call its own HTTP API** (TD-01); it handles the equivalent failures through the same taxonomy | TD-01 |
| PEH-9.7 | **Both surfaces present the same failure identically** in meaning and wording | SCC-2.4 |

---

## 10. Accessibility of errors

| ID | Rule | Source |
| --- | --- | --- |
| PEH-10.1 | **Errors are announced to assistive technology**, not merely shown | A11 §8 |
| PEH-10.2 | **Focus moves to the first error** on a failed submission | A11 §8 |
| PEH-10.3 | **Errors are programmatically associated with their field** | WCAG 3.3.1 |
| PEH-10.4 | **Colour is never the only error indicator** — text and an icon accompany it | WCAG 1.4.1 |
| PEH-10.5 | **A transient message is never the only record of a failure** that still needs action | A11 §8 |
| PEH-10.6 | **A message is not dismissed on a timer** when it requires action; it persists until resolved | WCAG 2.2.1 |
| PEH-10.7 | **Error text meets contrast requirements on every surface and under every host theme** | TM-5.4 |
| PEH-10.8 | **Recovery is completable with the keyboard alone and with a screen reader alone** | WCAG 2.1.1 |
| PEH-10.9 | **No error recovery requires a CAPTCHA, puzzle or memory test** | WCAG 3.3.8 |

---

## 11. Surface differences

| | **Web** | **Telegram Mini App** |
| --- | --- | --- |
| Page-level error | Full page in the normal layout | Same, inside host chrome, within safe-area insets |
| Onward route | Header, breadcrumb, footer, browser back | Header, breadcrumb, **host Back** |
| Inline errors | Identical | Identical; **must not sit under the keyboard** (TM-4.11) |
| Transient messages | Identical | Identical; **above safe-area insets** (TM-4.5) |
| Refresh | Browser refresh available | **No browser refresh** — an explicit in-page retry is always provided |
| Launch context failure | Not applicable | Degrades to Guest, with no error shown |
| External return lost | Not applicable | Surface stays usable; email OTP offered |
| Error copy | Identical | Identical |

| ID | Rule |
| --- | --- |
| PEH-11.1 | **The absence of browser refresh in the Mini App means every recoverable failure must carry its own retry control** |
| PEH-11.2 | **No surface shows an error the other would not**, except the two host-specific rows above (SCC §4) |
| PEH-11.3 | **No surface shows more internal detail than the other** |

---

## 12. Verification

| ID | Check |
| --- | --- |
| PEH-12.1 | No response exposes a stack trace, SQL, path or internal identifier |
| PEH-12.2 | No server error returns a success status |
| PEH-12.3 | No error page is indexable |
| PEH-12.4 | A failed write preserves input and offers retry |
| PEH-12.5 | No success state appears for an unaccepted write |
| PEH-12.6 | A retried idempotent submission creates no duplicate |
| PEH-12.7 | An interrupted action completes after re-authentication |
| PEH-12.8 | No authentication failure discloses account existence |
| PEH-12.9 | No rate-limit response discloses the limit |
| PEH-12.10 | Privileged content returns absence, not refusal |
| PEH-12.11 | A failed block leaves the rest of the page rendered |
| PEH-12.12 | A failed map leaves the address readable |
| PEH-12.13 | A failed Sponsored block renders nothing rather than something unlabelled |
| PEH-12.14 | Denied geolocation offers Area selection and is not reported as an error |
| PEH-12.15 | An unknown URL reaches the nearest valid ancestor with search offered |
| PEH-12.16 | Removed content returns gone, not a redirect to Home |
| PEH-12.17 | Errors are announced and receive focus; recovery works by keyboard and by screen reader alone |
| PEH-12.18 | Error text passes contrast under light and dark host themes |
| PEH-12.19 | Inline errors are not covered by the keyboard in the Mini App |
| PEH-12.20 | Every recoverable failure in the Mini App offers an in-page retry |
| PEH-12.21 | An unrecognised error code produces a safe generic message |
| PEH-12.22 | The same failure produces the same wording on both surfaces |

---

## 13. Open items

| ID | Item | Status |
| --- | --- | --- |
| OT-02 | OTP attempt limits — bounds rate-limit messaging | **Open — technical decision** |
| OT-07 | Observability — determines how correlation references are traced | **Open — technical decision** |
| D-21 | Maps vendor — determines map failure modes | Owner decision |
| D-23 | Report categories — determines report validation errors | **Owner decision, open** |
| D-14 | Permission set — determines permission-refusal cases | **Owner decision, open** |
| — | The stable error-code catalogue | **Open — technical decision**; PEH-9.1 binds it |
| — | Whether remaining attempt counts are safe to display | **Open — platform decision**, pending `docs/50-security/` |

---

## Decision references

D-14, D-21, D-23.
