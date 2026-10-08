# Interaction and Permission Model

| | |
| --- | --- |
| **Document** | Interaction and Permission Model |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

**Purpose.** The definitive answer to "who can do what, and does it require
an account?". [`prd-v1.0.md`](prd-v1.0.md) §17 states the principles; this
document holds the matrix. Where the two disagree, this document is the
detail and the PRD is the principle — neither may contradict
[`../60-decisions/decision-register.md`](../60-decisions/decision-register.md).

**Terminology** is fixed by [`glossary.md`](glossary.md).

---

## 1. Actors

| Actor | Authentication | Exists in V1 |
| --- | --- | --- |
| **Guest** | None | Yes |
| **Customer** | Google or email OTP (D-48) | Yes |
| **Operator** | Staff credentials, strength **Open (D-45)** | Yes |
| **Administrator** | Staff credentials, strength **Open (D-45)** | Yes |
| **Business owner** | — | **No (D-54)** — not a platform actor |

---

## 2. Access classes

| Class | Definition | Design obligation |
| --- | --- | --- |
| **Guest-safe** | Full function with no account | **MUST NOT** be gated, teased, blurred, truncated or interrupted by sign-in prompts |
| **Authenticated-required** | Needs a Customer account because the action is attributed to a person or stored against them | Sign-in offered **in context**, returning to the task (PRD ACC-3) |
| **Staff-only** | Operations console capabilities | Never exposed, hinted at, or linked from public surfaces |
| **Administrator-only** | Higher-risk staff actions | Enforced server-side; audited |
| **Future** | Not available to anyone in V1 | No placeholder UI, no disabled control, no "coming soon" |

---

## 3. Public capability matrix

G = Guest · C = Customer · O = Operator · A = Administrator
✅ permitted · ❌ not permitted · — not applicable

| Capability | G | C | O | A | Class |
| --- | :-: | :-: | :-: | :-: | --- |
| View homepage (C-01) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Search (C-02) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Use autocomplete (C-03) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Browse categories (C-04) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Browse areas (C-05) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| View category × area pages (C-06) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Use nearby discovery (C-07) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| View a Business profile (C-08) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| See hours and open status (C-09) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| View map / open in maps (C-10) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Use contact actions (C-11) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| See trust indicators (C-12) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| **Read** Reviews (C-13) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| **Write** a Review (C-13) | ❌ | ✅ | ❌¹ | ❌¹ | Authenticated-required |
| Edit own Review (C-35) | ❌ | ✅ | ❌ | ❌ | Authenticated-required |
| Delete own Review (C-35) | ❌ | ✅ | ❌ | ❌ | Authenticated-required |
| Save a Business (C-14) | ❌ | ✅ | — | — | Authenticated-required |
| View Saved list (C-34) | ❌ | ✅ | — | — | Authenticated-required |
| Report a problem with a Listing (C-15) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| Report a Review (C-15) | ❌ | ✅ | ✅ | ✅ | Authenticated-required² |
| Share a profile (C-17) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| View static and policy pages (C-18) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| See Sponsored placements (C-16) | ✅ | ✅ | ✅ | ✅ | Guest-safe |
| View own profile (C-33) | ❌ | ✅ | — | — | Authenticated-required |
| Delete own account (C-36) | ❌ | ✅ | — | — | Authenticated-required |

¹ Staff **MUST NOT** write Reviews from staff accounts. A staff member acting
as a private individual uses their own Customer account; writing Reviews for
Businesses Bulbula lists is a conflict of interest and is prohibited by
`review-policy.md` §7.
² Requires authentication because a report against a person's content must be
attributable and rate-limitable. Reporting a *Listing* does not, because the
subject is a Business, not a person (PRD TS-2).

### 3.1 Guest-safe rules

| ID | Rule |
| --- | --- |
| GS-1 | The complete discovery journey — arrive, search, filter, open a profile, call the business — **MUST** be possible without an account |
| GS-2 | Content **MUST NOT** be partially hidden behind authentication (no truncated reviews, no blurred numbers, no "sign in to see more") |
| GS-3 | A sign-in prompt **MUST** appear only as a direct consequence of a Guest attempting an authenticated-required action |
| GS-4 | No interstitial, modal wall, countdown or nag may interrupt a Guest journey |
| GS-5 | A Guest **MUST** be able to use the product indefinitely without ever creating an account |

### 3.2 Why each authenticated-required item requires an account

| Action | Reason |
| --- | --- |
| Write / edit / delete a Review | Must be attributable to a person; required for anti-abuse and for moderation accountability (D-12) |
| Save / Saved list | Stored against a person and synchronised across surfaces (C-32) |
| Profile, deletion, privacy controls | Operate on that person's own data |
| Report a Review | Attribution and rate limiting against targeted abuse |

---

## 4. Staff capability matrix

| Capability | O | A | Notes |
| --- | :-: | :-: | --- |
| Access the operations console | ✅ | ✅ | Web-only |
| Create a Listing (C-19) | ✅ | ✅ | Requires a linked Permission record |
| Edit a Listing (C-20) | ✅ | ✅ | Restricted fields **Open (D-14)** |
| Submit a Listing for review (C-21) | ✅ | ✅ | |
| Approve / publish a Listing (C-21) | ✅³ | ✅ | Not the Listing's own creator where another reviewer exists |
| Unpublish a Listing | ✅ | ✅ | Reason required |
| Record Verification (C-21) | ✅ | ✅ | |
| Manage media (C-22) | ✅ | ✅ | |
| Manage categories (C-23) | ❌ | ✅ | Administrator-only |
| Manage locations / areas (C-24) | ❌ | ✅ | Administrator-only |
| Moderate Reviews (C-25) | ✅ | ✅ | Policy changes and appeals: Administrator |
| Handle reports (C-26) | ✅ | ✅ | Legal escalations: Administrator |
| Create a Campaign (C-27) | ✅ | ✅ | |
| Approve / activate a Campaign (C-27) | ❌ | ✅ | Administrator-only |
| Terminate a Campaign early (C-27) | ❌ | ✅ | Reason required |
| View operational analytics (C-28) | ✅⁴ | ✅ | |
| Read the Audit log (C-29) | ❌ | ✅ | Administrator-only |
| Create / disable staff accounts | ❌ | ✅ | Administrator-only |
| Assign roles | ❌ | ✅ | Administrator-only |
| Execute a Customer data or deletion request | ❌ | ✅ | Administrator-only; audited |
| Change published policy content (C-18) | ❌ | ✅ | Administrator-only |

³ Subject to the separation-of-duties rule in PRD C-21; where Bulbula
operates with a single Staff member the gap is recorded as an accepted
operational risk, not removed from the product.
⁴ Scope of an Operator's analytics view (own work versus all work) is **Open
(D-14)**.

### 4.1 Staff rules

| ID | Rule |
| --- | --- |
| ST-1 | Staff accounts are created by Administrators. There is no staff self-registration |
| ST-2 | Staff accounts are separate from Customer accounts (PRD ACC-8) |
| ST-3 | Staff authentication **MUST** be stronger than Customer authentication and **MUST NOT** be email OTP alone — **Open (D-45)** |
| ST-4 | Authorisation is enforced server-side for every action |
| ST-5 | Every consequential staff action is audited (C-29) |
| ST-6 | Least privilege applies; the precise Operator/Administrator boundary is **Open (D-14)** and the table above is the starting position for that decision |

---

## 5. Business owner — explicitly no permissions

| Action | V1 | Decision |
| --- | --- | --- |
| Create an account | ❌ | D-54 |
| Log in | ❌ | D-54 |
| Create a Listing | ❌ | D-02 |
| Claim a Listing | ❌ | D-02 |
| Edit a Listing | ❌ | D-02 |
| Upload media | ❌ | D-02 |
| Reply to a Review | ❌ | D-12 |
| Buy advertising directly | ❌ | D-10 |
| View analytics | ❌ | D-54 |

**What a Business owner *can* do in V1** — all of it offline or through the
public correction path, mediated by Staff:

| Route | Mechanism |
| --- | --- |
| Grant or withdraw Permission | In person or by contact with an Operator (D-50; PRD §14.3) |
| Supply or update information | Collected by an Operator; applied as an edit (C-20) |
| Request a correction | Contact page or the public report path (C-15, C-18) |
| Request removal of a photograph | Contact; executed by an Operator (C-22) |
| Request unpublication | Contact; executed by Staff (PRD TS-5) |
| Buy sponsorship | Offline discussion with Staff, who create the Campaign (C-27) |

**Design consequence.** Because there is no owner account, every one of these
routes **MUST** be reachable without one: the contact and corrections page is
a required V1 page (C-18), and the report control is required on every
profile (TS-1).

---

## 6. Future — not implemented in V1

Listed so that nobody builds them early and nobody is surprised later.
Direction only; no V1 artefact may anticipate them (PRD H-4).

| Future capability | Gate |
| --- | --- |
| Business accounts | D-54 — with **mandatory Administrator approval of every submission** |
| Owner-created Listings and claims | D-02 — same approval gate |
| Owner replies to Reviews | Requires business accounts |
| Self-service advertising | Requires business accounts and L-17/L-20 |
| Owner analytics | Requires business accounts |

---

## 7. Enforcement requirements

| ID | Requirement |
| --- | --- |
| ENF-1 | Every restriction in this document **MUST** be enforced server-side. Client-side hiding is presentation, never protection |
| ENF-2 | An unauthorised request **MUST** fail safely and **MUST NOT** disclose whether the target exists |
| ENF-3 | Attempts to perform staff actions without rights **MUST** be logged |
| ENF-4 | Guest-safe capabilities **MUST** be verified as reachable without a session in testing |
| ENF-5 | Any new capability **MUST** be assigned an access class in this document before it is built |

---

## Decision references

D-02, D-05, D-10, D-12, D-14, D-45, D-48, D-49, D-50, D-54.
