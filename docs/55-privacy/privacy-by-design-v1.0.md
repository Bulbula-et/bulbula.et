# Privacy by Design

| | |
| --- | --- |
| **Document** | Privacy by Design — V1 |
| **Version** | v1.0 |
| **Status** | Draft |
| **Date** | 2026-10-07 |
| **Owner** | Project owner (Bulbula) |
| **Supersedes** | — |

## Scope

The design rules that make privacy a property of the system rather than
a policy about it: default states, collection, publication, visibility,
Saves, reporter protection, location, photography, search and caching,
analytics, deletion, Telegram, and the design review checklist.

**Out of scope.** Governance (`privacy-governance-v1.0.md`), the data
itself (`data-inventory-v1.0.md`), periods
(`data-retention-v1.0.md`), request handling
(`data-subject-rights-v1.0.md`).

## Authority

Below approved product, UX and platform specifications. **Changes no
product decision.** Where a design rule here would alter approved
behaviour, it is raised as an open item instead.

> **Art. 49 makes this a legal duty.** Art. 49(1) requires appropriate
> technical and organisational measures **at the time of determining the
> means and at the time of processing**. Art. 49(2) requires that **by
> default only personal data necessary for each specific purpose is
> processed** — covering amount collected, extent of processing, storage
> period and accessibility — and expressly that **personal data must not
> by default be made accessible to an indefinite number of people
> without the individual's intervention.**

---

## 1. Principles

| ID | Principle | Consequence |
| --- | --- | --- |
| PBD-1.1 | **Not collecting is the strongest control** | A field that does not exist cannot leak, be requested, be subpoenaed or be retained too long |
| PBD-1.2 | **The default is the policy.** Most people never change a default | A privacy-hostile default is a privacy-hostile product |
| PBD-1.3 | **Publication is irreversible.** Once indexed, copied or cached, it is beyond recall | Publishing is the highest-risk operation in the system |
| PBD-1.4 | **Privacy is a design property, not a document** | A notice describing a bad design does not fix the design |
| PBD-1.5 | **Convenience is not a purpose.** "It might be useful later" fails Art. 13 | PRIV-2 |
| PBD-1.6 | **Data has gravity.** Stored data attracts new uses, integrations and requests | Every field is a permanent liability |
| PBD-1.7 | **A security control that collects more personal data is a privacy change** | PG-11.4 |

---

## 2. Default states

| ID | Behaviour | Default | Why |
| --- | --- | --- | --- |
| PBD-2.1 | Browsing, searching, viewing listings | **No account required** | GS-1. The core product processes almost nothing |
| PBD-2.2 | Saved listings | **Private, always; no public option exists** | PRIV-3 |
| PBD-2.3 | Customer profile pages | **Do not exist** | No public surface aggregating a person's activity |
| PBD-2.4 | Review attribution | **Display name only; never email** | DI-3.2 |
| PBD-2.5 | Reporter identity | **Never exposed to anyone outside permitted staff** | TS-8 |
| PBD-2.6 | Precise location | **Off until the user acts** | LOC-1 |
| PBD-2.7 | Marketing email | **Does not exist in V1** | D-24 |
| PBD-2.8 | Third-party analytics and advertising pixels | **Do not exist** | AN-1 |
| PBD-2.9 | Behavioural profiling | **Does not exist** | PG-8.5 |
| PBD-2.10 | Offline storage of personal data on the device | **Does not exist** | No offline support in V1 |
| PBD-2.11 | Client-side session token | **Does not exist** | PD-05 |
| PBD-2.12 | Public caches | **Contain no personal account data** | PCP-4 |

| ID | Rule |
| --- | --- |
| PBD-2.13 | **Every default above is the privacy-protective one.** That is the Art. 49(2) test, and it is already met by the approved design |
| PBD-2.14 | **Changing any default in this table is a product decision with a privacy review**, never an implementation choice |
| PBD-2.15 | **No dark pattern.** A privacy-reducing choice must not be visually favoured, pre-ticked or buried |

---

## 3. Anonymous by default

| ID | Statement | Source |
| --- | --- | --- |
| PBD-3.1 | **The main use of Bulbula requires no identity at all.** Finding a business is anonymous | GS-1 |
| PBD-3.2 | An account is required only for **Saves, Reviews and reports** — the features that are intrinsically personal | D-12 |
| PBD-3.3 | **No feature is gated behind an account to harvest accounts** | Art. 8(4) |
| PBD-3.4 | **No sign-in wall, no interstitial, no "sign in to see more"** | PBD-3.1 |
| PBD-3.5 | Anonymous use still produces **server logs containing IP addresses** — personal data (DI-7.14). Anonymous does not mean no processing | DI-2.2 |
| PBD-3.6 | **Anonymous users get the same content.** There is no degraded experience designed to pressure sign-up | UFL |

---

## 4. Minimisation in collection

| ID | Rule | Source |
| --- | --- | --- |
| PBD-4.1 | **Every field has a stated purpose before it exists** | PRIV-2 |
| PBD-4.2 | **A field with no purpose is removed, not retained for later** | PBD-1.5 |
| PBD-4.3 | **No speculative collection.** "We may want analytics on this" is not a purpose | D-51 |
| PBD-4.4 | **Google sign-in imports only email and the account identifier** — no photo, no profile, no scopes beyond what sign-in needs | DI-3.13 |
| PBD-4.5 | **OAuth scopes are the narrowest that work**, and adding a scope is a privacy change | AS-5 |
| PBD-4.6 | **No date of birth** (PG-9.4), **no phone number for accounts**, **no password**, **no profile photo**, **no address for Customers** | DI-3.8…DI-3.11 |
| PBD-4.7 | **Free-text fields are bounded in length** and are not an invitation to disclose more | APP-2.9 |
| PBD-4.8 | **A listing collects what is needed to find and contact a business**, not everything knowable about it | D-50 |
| PBD-4.9 | **Personal contact points are collected only where there is no business alternative**, and are marked as such | PCP-2 |
| PBD-4.10 | **EXIF is stripped at upload**; the system never stores metadata it did not need | APP-6.11 |
| PBD-4.11 | **Errors and logs capture context, not records** | APP-9.1 |
| PBD-4.12 | **Adding a field is a reviewed change** (PG §11), including fields added by a security control | PBD-1.7 |

---

## 5. Publication

Publication is where Bulbula's privacy risk actually lives.

| ID | Rule | Source |
| --- | --- | --- |
| PBD-5.1 | **What is published is a deliberate, enumerated set** — never "whatever is in the record" | PCP-3 |
| PBD-5.2 | **Internal fields are never rendered**: provenance, permission records, verification evidence, moderation notes, reporter identity, staff identity, audit data | PCP-5 |
| PBD-5.3 | **A personal contact point is published only with a recorded Permission basis** | D-50, D-43 |
| PBD-5.4 | **Publication is presumptively permanent.** Design as though every published value is already copied | PBD-1.3 |
| PBD-5.5 | **Structured data, API responses, sitemaps, share previews and feeds publish no more than the page does** | PSE-2.2 |
| PBD-5.6 | **No bulk export of listing data is offered**, and scraping is accepted but not assisted | THR-08 |
| PBD-5.7 | **Reviews are published with a display name, not an email, not an identifier** | PBD-2.4 |
| PBD-5.8 | **Art. 49(2) expressly targets this**: personal data must not by default be accessible to an indefinite number of people without the individual's intervention. Publication must therefore be an **act**, not a default | Art. 49(2) |
| PBD-5.9 | **The person whose data is published may never have interacted with Bulbula** (DI-2.3). Their intervention cannot be assumed — which makes PCP-2 and the no-account correction route load-bearing | DI-2.9 |

---

## 6. Visibility rules

| Data | Public | Signed-in owner | Permitted staff | Notes |
| --- | --- | --- | --- | --- |
| Listing core fields | ✅ | ✅ | ✅ | The product |
| Business contact points | ✅ | ✅ | ✅ | PCP-2 applies |
| Published Review + display name | ✅ | ✅ | ✅ | — |
| Review author's email | ❌ | ✅ own | ✅ task-based | Never public |
| **Saved listings** | ❌ | ✅ own only | **Task-based, logged** | PRIV-3 |
| **Reporter identity** | ❌ | ✅ own | **Permitted staff only** | TS-8 |
| **Reviewer identity beyond display name** | ❌ | ✅ own | Task-based | Never exposed to the business |
| Moderation decisions and policy basis | ❌ | Outcome to the affected party | ✅ | TS-9 |
| Provenance and permission records | ❌ | ❌ | ✅ | PCP-5 |
| Audit trail | ❌ | ❌ | ✅ restricted | SO-4.6 |
| Analytics aggregates | Owner's discretion | — | ✅ | PCP-4 thresholds |

| ID | Rule |
| --- | --- |
| PBD-6.1 | **There is no business-facing surface in V1** (D-02, D-54), so a business cannot see who reviewed or reported it. This is a privacy feature and must survive the eventual introduction of business accounts |
| PBD-6.2 | **Reviewer identity beyond the display name is never disclosed to a business**, by any channel, including informally |
| PBD-6.3 | **Staff visibility is task-based and logged**, never ambient | PG-10.2 |
| PBD-6.4 | **A moderation outcome may be communicated without disclosing who reported it** | TS-8 |

---

## 7. No tracking

| ID | Statement | Source |
| --- | --- | --- |
| PBD-7.1 | **No behavioural advertising.** Advertising is fixed packages sold by staff | D-10 |
| PBD-7.2 | **Paid placement never affects organic ranking** | D-05 |
| PBD-7.3 | **No sale, rental or sharing of customer data** for anyone's marketing | DI-10.13 |
| PBD-7.4 | **No cross-site tracking, no third-party pixels, no advertising SDKs** | AN-1 |
| PBD-7.5 | **No profiling, no behavioural segmentation, no interest inference** | PG-8.5 |
| PBD-7.6 | **No per-user view history** is built | DI-8.2 |
| PBD-7.7 | **Analytics are aggregate and non-identifying**, with thresholds | AN-3, PCP-4 |
| PBD-7.8 | **No analytics identifier is joined to an account identifier** | AN-3 |
| PBD-7.9 | **Art. 11(4) prohibits marketing, profiling and profile merging for minors.** Because none of these exist for anyone, Bulbula meets that structurally | Art. 11(4) |
| PBD-7.10 | **Introducing any of the above is a product decision**, not an optimisation, and requires a fresh Art. 31 and Art. 47 analysis | PG-8.4 |

---

## 8. Location

| ID | Rule | Source |
| --- | --- | --- |
| PBD-8.1 | **Precise location is requested only when the user invokes a location feature**, with a clear explanation first | LOC-1 |
| PBD-8.2 | **Precise location is used transiently and not persisted** | LOC-2 |
| PBD-8.3 | **No background location, no continuous tracking, no location history** | LOC-3 |
| PBD-8.4 | **Denial is a first-class path**, not a degraded dead end | LOC-4 |
| PBD-8.5 | **Coarse location is preferred wherever it is sufficient** | LOC-5 |
| PBD-8.6 | **Location data is expressly personal data** (Art. 2(2)); a persisted coordinate tied to a session is a tracking record | Art. 2(2) |
| PBD-8.7 | **`Permissions-Policy: geolocation=()` stays until Nearby ships**, and then becomes `self`, not a wildcard | APP-7.5 |
| PBD-8.8 | **A map request may disclose location to the maps vendor** even if Bulbula stores nothing — that is still a transfer (DI-10.4) | L-19 |

---

## 9. Photography

A listing photograph is the most casually created privacy risk in the
product.

| ID | Rule | Source |
| --- | --- | --- |
| PBD-9.1 | **Photographs are of premises, not of people.** The purpose is to show a place | D-50 |
| PBD-9.2 | **Incidental individuals should be avoided at capture** — wait, reframe, or return later. The cheapest control is behavioural | PBD-1.1 |
| PBD-9.3 | **Where an individual is identifiable and incidental, prefer a different photograph** rather than publishing and hoping | PBD-9.2 |
| PBD-9.4 | **Where an individual is deliberately featured** — an owner posing for their shopfront — that is a person's data, requiring a recorded basis, not an assumption | D-43 |
| PBD-9.5 | **EXIF, including GPS and device identifiers, is stripped before storage** | APP-6.11 |
| PBD-9.6 | **The retained original is also stripped** (DI-4.20) | DI-4.16 |
| PBD-9.7 | **A removal route exists for any identifiable person in a photograph, with no account required** | PBD-9.9 |
| PBD-9.8 | **A photograph must not be used to reveal something the business did not intend** — a visible home interior, a vehicle plate, a document on a counter, a child |
| PBD-9.9 | **A request to be removed from a photograph is honoured by removing or replacing the photograph**, not by arguing about identifiability | DSR §6 |
| PBD-9.10 | **No facial recognition, blurring automation or person detection exists.** Review is human | DI-4.23 |
| PBD-9.11 | **Whether, and on what basis, Bulbula may photograph premises and people is PENDING COUNSEL (L-18).** Until then PBD-9.2 and PBD-9.3 apply strictly | L-18 |
| PBD-9.12 | **Photographs supplied by a business are not thereby lawful.** The business's Permission does not cover a third party in the frame | PG-7.7 |

---

## 10. Search, caching and indexing

| ID | Rule | Source |
| --- | --- | --- |
| PBD-10.1 | **Public caches contain no personal account data** — no Saves, no email, no session-derived content | PCP-4 |
| PBD-10.2 | **A cache key never contains a personal identifier** | PCP-4 |
| PBD-10.3 | **Signed-in and anonymous responses are never shared in the same cache entry** | TD-05 |
| PBD-10.4 | **Search indexes only published content** | PBD-5.1 |
| PBD-10.5 | **Search queries are not attached to identities for behavioural purposes**; query logging granularity is **D-27, open** | DI-8.9 |
| PBD-10.6 | **Unpublishing must invalidate caches promptly**, or deletion is theatre | DSR §5 |
| PBD-10.7 | **Non-production environments are not indexable** | SO-7.5 |
| PBD-10.8 | **Bulbula cannot remove content from third-party search caches**; it can only remove the source and request de-indexing. **This limit is stated honestly to data subjects** | DI-10.8 |
| PBD-10.9 | **No page exposes an internal identifier** that would allow enumeration of people | APP-2.4 |

---

## 11. Deletion

| ID | Rule | Source |
| --- | --- | --- |
| PBD-11.1 | **Deletion is designed in, not bolted on.** Every table holding personal data has an answer to "what happens on deletion?" | Art. 50 |
| PBD-11.2 | **Art. 50 requires destruction preventing reconstruction in intelligible form** — a stronger standard than a status flag | Art. 50 |
| PBD-11.3 | **Where a record must survive for accountability, what survives is the minimum**, not the whole record | SO-4.4 |
| PBD-11.4 | **Deletion is honest about backups** (RET §8) | SO-8.10 |
| PBD-11.5 | **The mechanism is settled: a Review is withdrawn, not destroyed** (D-34). Public visibility ceases and it leaves every rating summary, while an internal record may persist for retention, audit, abuse and legal purposes. **How long that record persists is PENDING COUNSEL (L-21, D-46)** and must be answered before launch, because it is the first question a deleting user asks | D-34, D-46 |
| PBD-11.6 | **A Review is personal data about the author, and it belongs to a Branch** (D-34, D-55). Publishing it exposes only the identity necessary for attribution — a display name — never the email address, never the account identifier, never any other Branch or Business linkage | PCP-3, D-55 |
| PBD-11.6 | **Deletion must propagate to caches, derived media, search indexes and exports** | PBD-10.6 |
| PBD-11.7 | **Art. 50(2) requires notifying processors of the destruction obligation** | Art. 50 |

---

## 12. Telegram

| ID | Rule | Source |
| --- | --- | --- |
| PBD-12.1 | **The Mini App is the same backend and the same privacy rules.** No surface has weaker protection | D-49 |
| PBD-12.2 | **The Mini App must not expose more data than the Web does** | TM-7 |
| PBD-12.3 | **Telegram is a third party that sees usage of the surface.** That is disclosed, not hidden | DI-10.3 |
| PBD-12.4 | **D-33 Telegram identity is open and is not a login mechanism** (AS-10). No Telegram identity data is stored until D-33 is decided | D-33 |
| PBD-12.5 | **Telegram's receipt of data is a cross-border transfer question** | L-12 |
| PBD-12.6 | **No personal data is placed in a Mini App start parameter, deep link or URL** | APP-2.4 |

---

## 13. Design review checklist

Applied to every change touching personal data.

| # | Question | Fail condition |
| --- | --- | --- |
| 1 | What personal data does this add or newly use? | Cannot answer |
| 2 | What is the specific purpose? | "Might be useful" |
| 3 | Could the feature work with less? | Yes, but we collect more anyway |
| 4 | Could it work with none? | Yes, but we collect anyway |
| 5 | Is the default the privacy-protective one? | No |
| 6 | Who can see it? | More people than need to |
| 7 | Does anything become public? | Yes, without an explicit decision |
| 8 | Is there a retention answer? | No |
| 9 | Does deletion work end to end, including caches and derivatives? | No |
| 10 | Does a new recipient or vendor appear? | Yes, unassessed |
| 11 | Does data leave Ethiopia? | Yes, without a transfer basis |
| 12 | Does it evaluate a person automatically? | Yes, without a human route |
| 13 | Does it affect minors differently? | Unconsidered |
| 14 | Is the inventory updated? | No |
| 15 | Does the notice need to change? | Yes, and it hasn't |
| 16 | Does it quietly change an approved product decision? | **Yes — stop and raise it** |

| ID | Rule |
| --- | --- |
| PBD-13.1 | **Any failed row blocks the change** until resolved or explicitly accepted by the owner |
| PBD-13.2 | **Row 16 is absolute.** A privacy or security change may not alter a product decision; it must surface it (PG §4.4) |
| PBD-13.3 | **The checklist is evidence of Art. 49 compliance** and its use should be recorded |

---

## 14. Unresolved items

| ID | Item | Status |
| --- | --- | --- |
| L-21 / D-46 | **Retention period** for a withdrawn Review after account deletion | **PENDING COUNSEL.** D-34 settled the mechanism on 2026-10-07; the duration is the remaining gap in the deletion story |
| D-27 | Analytics granularity and search-query logging | **Open — privacy decision** |
| D-43 | Permission record contents, including photography | **Open — privacy decision** |
| D-33 | Telegram identity | **Open — product decision** |
| D-25 | Media storage, originals and derivatives | **Open — technical decision** |
| D-21 | Maps provider and what it receives | **Open — technical decision** |
| D-46 | Minimum account age | **PENDING COUNSEL** |
| L-18 | Photography of premises and people | **PENDING COUNSEL** |
| L-19 | Maps terms | **PENDING COUNSEL** |
| — | Minimum aggregate thresholds before publishing a count | **Open — privacy decision** |
| — | Whether Bulbula requests de-indexing on removal, and when | **Open — product decision** |
| — | Whether a person depicted may request removal anonymously | **Open — privacy decision** |

---

## Legal and regulatory references

| Reference | Relevance | Classification |
| --- | --- | --- |
| Proclamation 1321/2024, **Art. 49(1)** | Measures at the time of determining means **and** at the time of processing | Confirmed — statute/regulation |
| **Art. 49(2)** | **By default only necessary data**; covers amount, extent, storage period and **accessibility**; data **not accessible by default to an indefinite number of people without the individual's intervention** | Confirmed — statute/regulation |
| Art. 6(2)(c) | Adequate, relevant and not excessive | Confirmed — statute/regulation |
| Art. 13 | Purpose limitation | Confirmed — statute/regulation |
| Art. 2(2) | Location data and online identifiers are personal data | Confirmed — statute/regulation |
| Art. 11(4) | No marketing, profiling or profile merging for minors | Confirmed — statute/regulation |
| Art. 31(3) | No automated evaluation based on sensitive data | Confirmed — statute/regulation |
| Art. 50 | Destruction preventing reconstruction; notify processors | Confirmed — statute/regulation |
| **Whether these measures are "appropriate" under Art. 49(1)** | — | **Counsel interpretation required** |
| Photography and image rights | Outside Proclamation 1321/2024 | **PENDING COUNSEL (L-18)** |

---

## Decision references

D-02, D-05, D-10, D-12, D-21, D-24, D-25, D-27, D-33, D-34, D-43, D-46,
D-49, D-50, D-51, D-54, D-55.
