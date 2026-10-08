#!/usr/bin/env python3
"""Repository-wide documentation verifier (Phase 3.8).

Run from the repository root:

    python3 tools/verify-docs.py

Checks the whole of docs/ against the authority model recorded in
docs/60-decisions/decision-register.md. It is read-only, additive, and
independent of the application test suite: it never writes a file and never
touches src/, tests/ or CI.

Earlier phases used throwaway verifiers that lived outside the repository and
could not be re-run by anyone else. This file makes those checks durable.

Exit code 0 = pass, 1 = failure, 2 = could not run.
"""

from __future__ import annotations

import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DOCS = ROOT / "docs"
REGISTER = DOCS / "60-decisions" / "decision-register.md"
DISCOVERY = DOCS / "00-discovery"

# The audit document reports on defects, so it must be able to quote a wrong
# article number, a retired identifier or a forbidden term verbatim. It is
# exempt from the citation-style checks for the same reason the historical
# discovery layer is: it is a record *about* the corpus, not part of it.
# It is NOT exempt from control metadata, decision references or link checks.
META = DOCS / "45-quality" / "documentation-audit-v1.0.md"

# Documents that are deliberately historical. They are read for reasoning,
# never for current rules, and they are not edited to erase history.
HISTORICAL_STATUSES = {"Superseded", "Approved — historical record (frozen)"}

# Identifiers that exist only in the discovery layer and were consolidated or
# retired before the register was created.
RETIRED_IDS = {"D-46a", "D-46b", "L-1", "L-9", "L-11", "L-13", "L-14"}

# Infrastructure and frameworks the architecture forbids for V1. Each may be
# named only in a negative, rejected, future or historical framing.
FORBIDDEN_STACK = [
    "laravel", "symfony", "laminas", "mezzio", "dotkernel", "slim", "flight",
    "kubernetes", "elasticsearch", "opensearch", "meilisearch", "typesense",
    "solr", "kafka", "rabbitmq", "microservice", "redis", "memcached",
]

# Product capabilities excluded from V1. Same rule: negative framing only.
FORBIDDEN_FEATURES = [
    "business account", "claim this business", "claim flow", "owner reply",
    "owner replies", "owner dashboard", "self-service advertis",
    "self-serve advertis", "paid ranking", "paid verification",
    "paid inclusion", "behavioural advertis", "behavioral advertis",
    "sell customer data", "sale of customer data", "sign in with apple",
    "apple sign-in", "offline-first",
]

NEGATION = [
    "never", "no ", "not ", "none", "forbid", "prohibit", "exclud", "reject",
    "do not", "must not", "cannot", "without", "open (", "future", "v2",
    "post-v1", "supersede", "historical", "pending", "deferred",
    "out of scope", "does not", "is not", "are not", "nothing", "non-",
    "exclusion", "risk", "question", "deprecat", "wrong", "defect", "drift",
    "beyond v1", "later", "year one", "absent", "unless", "would ",
    "unavailable", "rather than", "instead of", "no-go", "ng-", "if the",
]

# The seven technical decisions that define the architecture. Each must be
# stated in the TRD and must not be contradicted elsewhere.
TECHNICAL_DECISIONS = ["TD-01", "TD-02", "TD-03", "TD-04", "TD-05",
                       "TD-06", "TD-07"]

results: list[tuple[bool, str]] = []


def check(ok: bool, label: str, detail: str = "") -> None:
    results.append((ok, label if not detail else f"{label} — {detail}"))


def strip_code(text: str) -> str:
    return re.sub(r"```.*?```", "", text, flags=re.DOTALL)


def control(text: str, field: str) -> str | None:
    m = re.search(rf"^\|\s*\*\*{field}\*\*\s*\|\s*(.+?)\s*\|\s*$",
                  text, re.MULTILINE)
    if not m:
        return None
    return m.group(1).replace("**", "").strip()


def load() -> dict[Path, str]:
    return {p: p.read_text(encoding="utf-8") for p in sorted(DOCS.rglob("*.md"))}


# ------------------------------------------------------------------ checks


def check_control_metadata(docs: dict[Path, str]) -> None:
    """1. Every document carries Version and Status."""
    missing_v = [str(p.relative_to(ROOT)) for p, t in docs.items()
                 if not control(t, "Version")]
    missing_s = [str(p.relative_to(ROOT)) for p, t in docs.items()
                 if not control(t, "Status")]
    check(not missing_v, "1a. every document has a Version", ", ".join(missing_v))
    check(not missing_s, "1b. every document has a Status", ", ".join(missing_s))

    bad = []
    for p, t in docs.items():
        if DISCOVERY in p.parents or p == REGISTER:
            continue
        v, s = control(t, "Version"), control(t, "Status")
        if v not in ("v1.0", "1.0"):
            bad.append(f"{p.name} Version={v}")
        if s != "Draft":
            bad.append(f"{p.name} Status={s}")
    check(not bad, "1c. current formal documents are v1.0 / Draft",
          "; ".join(bad))

    reg = docs.get(REGISTER, "")
    check(control(reg, "Status") == "Approved",
          "1d. the decision register is Approved",
          f"got {control(reg, 'Status')}")

    hist = [p.name for p in docs
            if DISCOVERY in p.parents
            and control(docs[p], "Status") not in HISTORICAL_STATUSES
            and control(docs[p], "Status") != "Approved"]
    check(not hist, "1e. discovery documents carry a historical status",
          ", ".join(hist))


def check_decision_ids(docs: dict[Path, str]) -> set[str]:
    """2. Every D-xx cited is real; retired IDs stay in the discovery layer."""
    register = docs.get(REGISTER)
    if register is None:
        check(False, "2. decision register present")
        return set()

    real = set(re.findall(r"\bD-\d+[a-z]?\b", register))
    check(len(real) >= 50, "2a. decision register parsed",
          f"{len(real)} identifiers")

    unknown: dict[str, set[str]] = {}
    for p, t in docs.items():
        if p == META:
            continue
        for did in re.findall(r"\bD-\d+[a-z]?\b", t):
            if did not in real:
                unknown.setdefault(did, set()).add(p.name)
    allowed = {d: f for d, f in unknown.items()
               if not all(DISCOVERY.name in str(n) or n.startswith(
                   "product-decision-brief") or n.startswith(
                   "project-understanding") or n.startswith("research-notes")
                   for n in f)}
    check(not allowed, "2b. no fictional D-xx outside the discovery layer",
          "; ".join(f"{d} in {', '.join(sorted(f))}"
                    for d, f in sorted(allowed.items())))

    leaked = []
    for p, t in docs.items():
        if DISCOVERY in p.parents or p == META:
            continue
        for rid in RETIRED_IDS:
            for m in re.finditer(rf"\b{re.escape(rid)}\b", t):
                tail = t[m.end():m.end() + 4]
                # "L-1…L-22" is a range pointing at the historical list,
                # not a citation of L-1 itself.
                if tail.lstrip().startswith(("\u2026", "...", "\u2013", "-")):
                    continue
                leaked.append(f"{p.name}: {rid}")
                break
    check(not leaked, "2c. no retired identifier cited as live",
          "; ".join(leaked))
    return real


def check_capabilities(docs: dict[Path, str]) -> None:
    """3. C-01..C-40 exist exactly once as a set, and are traced."""
    scope = DOCS / "10-product" / "scope-v1.md"
    text = docs.get(scope)
    if text is None:
        check(False, "3. scope-v1.md present")
        return
    found = set(re.findall(r"\bC-(\d{2})\b", text))
    expected = {f"{i:02d}" for i in range(1, 41)}
    check(found >= expected, "3a. scope defines C-01…C-40",
          "missing " + ", ".join(sorted(expected - found)))

    everywhere = set()
    for t in docs.values():
        everywhere |= set(re.findall(r"\bC-(\d{2})\b", t))
    beyond = sorted(c for c in everywhere if int(c) > 40 or int(c) < 1)
    check(not beyond, "3b. no capability beyond C-40 was invented",
          ", ".join(f"C-{c}" for c in beyond))

    # Each capability must be reachable from product, UX and technical layers.
    layers = {"UX": DOCS / "20-ux-ui", "TECH": DOCS / "30-technical"}
    gaps = []
    for name, d in layers.items():
        covered = set()
        for f in d.glob("*.md"):
            covered |= set(re.findall(r"\bC-(\d{2})\b",
                                      f.read_text(encoding="utf-8")))
        missing = sorted(expected - covered)
        if missing:
            gaps.append(f"{name}: " + ", ".join(f"C-{m}" for m in missing))
    check(not gaps, "3c. every capability appears in the UX and technical layers",
          "; ".join(gaps))


def check_requirement_ids(docs: dict[Path, str]) -> None:
    """4. Every TR-xxx cited is defined in the TRD."""
    trd = docs.get(DOCS / "30-technical" / "trd-v1.0.md")
    if trd is None:
        check(False, "4. TRD present")
        return
    defined = set(re.findall(r"\bTR-\d+[a-z]?\b", trd))
    unknown: dict[str, set[str]] = {}
    for p, t in docs.items():
        for tid in re.findall(r"\bTR-\d+[a-z]?\b", t):
            if tid not in defined:
                unknown.setdefault(tid, set()).add(p.name)
    check(not unknown, "4a. every TR-xxx cited is defined in the TRD",
          "; ".join(f"{i} in {', '.join(sorted(f))}"
                    for i, f in sorted(unknown.items())))

    for td in TECHNICAL_DECISIONS:
        check(td in trd, f"4b. {td} is stated in the TRD")


def check_links(docs: dict[Path, str]) -> None:
    """5. Relative links resolve; backticked docs/ paths exist or are framed."""
    link_re = re.compile(r"\[([^\]]*)\]\(([^)\s]+)(?:\s+\"[^\"]*\")?\)")
    broken = []
    for p, t in docs.items():
        for _, target in link_re.findall(t):
            if target.startswith(("http://", "https://", "mailto:", "#")):
                continue
            base = target.split("#")[0]
            if base and not (p.parent / base).resolve().exists():
                broken.append(f"{p.name} -> {target}")
    check(not broken, "5a. every relative link resolves", "; ".join(broken[:8]))

    path_re = re.compile(r"`(docs/[A-Za-z0-9._/\-]+\.md)`")
    dangling: dict[str, set[str]] = {}
    for p, t in docs.items():
        if DISCOVERY in p.parents or p == META:
            continue
        for ref in set(path_re.findall(t)):
            if (ROOT / ref).exists():
                continue
            for line in t.splitlines():
                if ref not in line:
                    continue
                low = line.lower()
                framed = any(w in low for w in (
                    "supersed", "to be", "produced when", "exists", "removed",
                    "relocat", "not performed", "historical", "pending",
                    "output", "@", "belong in"))
                if not framed:
                    dangling.setdefault(ref, set()).add(p.name)
    check(not dangling, "5b. a missing document is named only as future or history",
          "; ".join(f"{r} in {', '.join(sorted(f))}"
                    for r, f in sorted(dangling.items())))


def check_forbidden(docs: dict[Path, str], terms: list[str], label: str) -> None:
    """6/7. Forbidden stack and features appear only in negative framing."""
    offenders = []
    for p, t in docs.items():
        if DISCOVERY in p.parents or p == META:
            continue
        lines = strip_code(t).splitlines()
        headings: dict[int, str] = {}
        table_header = ""
        for i, raw in enumerate(lines, 1):
            low = raw.lower()
            if low.startswith("#"):
                level = len(low) - len(low.lstrip("#"))
                headings[level] = low
                for deeper in [k for k in headings if k > level]:
                    del headings[deeper]
                table_header = ""
            elif low.lstrip().startswith("|") and "---" not in low \
                    and not table_header:
                table_header = low
            elif not low.strip():
                table_header = ""
            lookback = " ".join(lines[max(0, i - 3):i - 1]).lower()
            context = " ".join([low, lookback, table_header,
                                *headings.values()])
            for term in terms:
                if term in low and not any(n in context for n in NEGATION):
                    offenders.append(f"{p.name}:{i} '{term}'")
    check(not offenders, label,
          "; ".join(offenders[:8]) + (" …" if len(offenders) > 8 else ""))


def check_invented_numbers(docs: dict[Path, str]) -> None:
    """8. No fabricated launch, throughput, price or staffing figure."""
    patterns = [
        (r"\bSLA of\b|\bSLA:\s*\d", "an SLA figure"),
        (r"\b\d{2,3}(\.\d+)?\s*%\s*(uptime|availability)", "an availability target"),
        (r"\b\d+\s+(listings|businesses|verifications)\s+(per|a)\s+(day|week|hour)",
         "a throughput target"),
        (r"\b(ETB|birr)\s*\d", "a price"),
        (r"\bstaff of \d|\b\d+\s*(FTE|full-time)", "a staffing level"),
        (r"\bminimum of \d+\s+(listings|businesses)", "a launch threshold"),
    ]
    offenders = []
    for p, t in docs.items():
        if DISCOVERY in p.parents:
            continue
        for i, line in enumerate(strip_code(t).splitlines(), 1):
            low = line.lower()
            if any(n in low for n in ("never", "no ", "not ", "none",
                                      "pending", "open", "[p]", "must not",
                                      "invent")):
                continue
            for pat, what in patterns:
                if re.search(pat, line, re.I):
                    offenders.append(f"{p.name}:{i} {what}")
    check(not offenders, "8. no fabricated figure in a current document",
          "; ".join(offenders[:8]))


def check_legal_sourcing(docs: dict[Path, str]) -> None:
    """9. Proclamation citations are sourced and use enacted numbering."""
    draft_era = [
        (r"Art\.? ?14\b.{0,40}consent", "draft-era 'consent at Art. 14'"),
        (r"Art\.? ?40\b.{0,40}adequacy", "draft-era 'adequacy at Art. 40'"),
        (r"Art\.? ?42\b.{0,40}derogation", "draft-era 'derogations at Art. 42'"),
        (r"under 16\b.{0,30}minor|minor.{0,30}under 16", "draft-era 'minors under 16'"),
    ]
    offenders = []
    for p, t in docs.items():
        if DISCOVERY in p.parents:
            continue
        lines = t.splitlines()
        for pat, what in draft_era:
            for i, line in enumerate(lines):
                if not re.search(pat, line, re.I):
                    continue
                low = " ".join(lines[max(0, i - 3):i + 2]).lower()
                # Documents that warn against the draft numbering quote it.
                if any(w in low for w in (
                        "draft", "earlier", "disregard", "not match",
                        "appear to derive", "secondary", "superseded",
                        "incorrect", "wrong", "not established")):
                    continue
                offenders.append(f"{p.name}: {what}")
                break
    check(not offenders, "9a. no draft-era article numbering", "; ".join(offenders))

    # Residency is Art. 22, never Art. 20 alone.
    wrong = []
    for p, t in docs.items():
        if DISCOVERY in p.parents or p == META:
            continue
        for i, line in enumerate(t.splitlines(), 1):
            if not re.search(r"Art\.? ?20\b", line):
                continue
            if not re.search(r"residen|sovereign|local storage|"
                             r"stored in Ethiopia", line, re.I):
                continue
            # Art. 22 may appear bare in a list such as "Art. 20, 21, 22".
            if re.search(r"\b22\b", line):
                continue
            # A statement scoped to transfer correctly cites Art. 20.
            if re.search(r"transfer|cross-border|in play|in use", line, re.I):
                continue
            wrong.append(f"{p.name}:{i}")
    check(not wrong, "9b. residency is cited as Art. 22, not Art. 20",
          "; ".join(wrong))

    # Any current document citing the Proclamation declares its sources.
    missing = []
    for p, t in docs.items():
        if DISCOVERY in p.parents:
            continue
        if re.search(r"Art\.? ?\d+", t) and \
                "## Legal and regulatory references" not in t:
            missing.append(p.name)
    check(not missing, "9c. documents citing the Proclamation list their sources",
          ", ".join(missing))


def check_pending_markers(docs: dict[Path, str]) -> None:
    """10. Unresolved matters stay unresolved."""
    joined = "\n".join(docs.values())
    for marker in ("PENDING COUNSEL", "PENDING PILOT"):
        check(marker in joined, f"10a. {marker} is still used")

    # No current document may describe an open decision as approved.
    register = docs.get(REGISTER, "")
    open_ids = set()
    m = re.search(r"## 2\. Open decisions(.*?)## 3\.", register, re.DOTALL)
    if m:
        open_ids = set(re.findall(r"\*\*(D-\d+[a-z]?)\*\*", m.group(1)))
    check(len(open_ids) >= 25, "10b. open decisions parsed from the register",
          f"{len(open_ids)} found")

    bad = []
    for p, t in docs.items():
        if DISCOVERY in p.parents:
            continue
        for i, line in enumerate(t.splitlines(), 1):
            for did in re.findall(r"\bD-\d+[a-z]?\b", line):
                if did not in open_ids:
                    continue
                low = line.lower()
                if re.search(rf"{re.escape(did.lower())}[^|]{{0,60}}"
                             r"\b(is approved|approved\b)", low) \
                        and "not approved" not in low \
                        and "approved provider" not in low \
                        and "approved login" not in low \
                        and "approved scope" not in low \
                        and "approved direction" not in low \
                        and "approved option" not in low:
                    bad.append(f"{p.name}:{i} {did}")
    check(not bad, "10c. no open decision is described as approved",
          "; ".join(bad[:8]))


def check_terminology(docs: dict[Path, str]) -> None:
    """11. Deprecated terms appear only as prohibitions."""
    banned = {
        "favourite": "Save", "favorite": "Save", "wishlist": "Save",
        "promoted placement": "Sponsored", "featured listing": "Sponsored",
        "business owner account": "no business account exists",
    }
    offenders = []
    for p, t in docs.items():
        if DISCOVERY in p.parents:
            continue
        lines = t.splitlines()
        for i, line in enumerate(lines, 1):
            low = line.lower()
            window = " ".join(lines[max(0, i - 3):i]).lower() + " " + low
            for term in banned:
                if term not in low:
                    continue
                if any(n in window for n in NEGATION):
                    continue
                # A glossary or content-design row that lists the term in a
                # "deprecated synonyms" column is forbidding it, not using it.
                if p.name in ("glossary.md", "content-design-v1.0.md",
                              "component-spec-v1.0.md") \
                        and low.lstrip().startswith("|"):
                    continue
                # Research documents cite external patterns by their names.
                if p.name == "ux-research-v1.0.md" and "pattern" in low:
                    continue
                offenders.append(f"{p.name}:{i} '{term}'")
    check(not offenders, "11. deprecated terminology appears only as a prohibition",
          "; ".join(offenders[:8]))


def check_decision_references(docs: dict[Path, str]) -> None:
    """12. Supporting documents declare the decisions they depend on."""
    exempt = {"prd-v1.0.md", "trd-v1.0.md", "decision-register.md"}
    missing = [p.name for p, t in docs.items()
               if DISCOVERY not in p.parents
               and p.name not in exempt
               and "## Decision references" not in t]
    check(not missing, "12. supporting documents list their decision references",
          ", ".join(missing))


# -------------------------------------------------------------------- main


def main() -> int:
    if not DOCS.is_dir():
        print("error: run this from the repository root", file=sys.stderr)
        return 2

    docs = load()
    check(len(docs) >= 60, "0. documentation tree loaded", f"{len(docs)} files")

    check_control_metadata(docs)
    check_decision_ids(docs)
    check_capabilities(docs)
    check_requirement_ids(docs)
    check_links(docs)
    check_forbidden(docs, FORBIDDEN_STACK,
                    "6. forbidden infrastructure appears only as rejected")
    check_forbidden(docs, FORBIDDEN_FEATURES,
                    "7. excluded V1 features appear only as exclusions")
    check_invented_numbers(docs)
    check_legal_sourcing(docs)
    check_pending_markers(docs)
    check_terminology(docs)
    check_decision_references(docs)

    failures = sum(1 for ok, _ in results if not ok)
    print("verify-docs")
    print("-" * 72)
    for ok, label in results:
        print(f"{'PASS' if ok else 'FAIL'}  {label}")
    print("-" * 72)
    print(f"{len(results) - failures} passed, {failures} failed")
    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
