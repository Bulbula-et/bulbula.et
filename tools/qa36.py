#!/usr/bin/env python3
"""Phase 3.6 QA verifier — docs/40-operations/ and docs/45-quality/."""
import re, sys, pathlib, collections

DRX = r"(?<![\w-])D-\d{2}[a-z]?(?![\w])"
ROOT = pathlib.Path("/home/user/bulbula.et")
OPS = ROOT / "docs/40-operations"
QUA = ROOT / "docs/45-quality"

EXPECTED = [
    OPS / "operations-model-v1.0.md",
    OPS / "listing-operations-v1.0.md",
    OPS / "moderation-operations-v1.0.md",
    OPS / "advertising-operations-v1.0.md",
    OPS / "customer-support-v1.0.md",
    OPS / "analytics-operations-v1.0.md",
    OPS / "observability-operations-v1.0.md",
    OPS / "backup-recovery-v1.0.md",
    OPS / "business-continuity-v1.0.md",
    QUA / "quality-strategy-v1.0.md",
    QUA / "test-strategy-v1.0.md",
    QUA / "release-management-v1.0.md",
    QUA / "production-readiness-v1.0.md",
    QUA / "maintenance-v1.0.md",
]

# local ID prefix -> home document
HOME = {
    "OM":  "operations-model-v1.0.md",
    "LO":  "listing-operations-v1.0.md",
    "MO":  "moderation-operations-v1.0.md",
    "AO":  "advertising-operations-v1.0.md",
    "SUP": "customer-support-v1.0.md",
    "ANO": "analytics-operations-v1.0.md",
    "OBS": "observability-operations-v1.0.md",
    "BR":  "backup-recovery-v1.0.md",
    "BC":  "business-continuity-v1.0.md",
    "QS":  "quality-strategy-v1.0.md",
    "TST": "test-strategy-v1.0.md",
    "REL": "release-management-v1.0.md",
    "PRR": "production-readiness-v1.0.md",
    "MNT": "maintenance-v1.0.md",
}

MARKERS = [
    "Open — operational decision", "Open — quality decision",
    "Open — implementation detail", "Open — product decision",
    "Open — technical decision", "PENDING COUNSEL", "PENDING PILOT",
]

# Documents named in the brief as future artefacts that must NOT be created.
MUST_NOT_EXIST = [OPS / "pilot-benchmark-v1.0.md"]

fail, warn = [], []


def err(doc, msg):
    fail.append(f"{doc}: {msg}")


# ---- load -------------------------------------------------------------
texts = {}
for p in EXPECTED:
    if not p.exists():
        err(p.name, "MISSING FILE")
    else:
        texts[p.name] = p.read_text(encoding="utf-8")

LATER_PHASES = {"documentation-audit-v1.0.md",      # Phase 3.8
                "implementation-plan-v1.0.md"}     # Phase 3.9; see tools/verify-docs.py
actual = sorted(p.name for p in list(OPS.glob("*.md")) + list(QUA.glob("*.md"))
                if p.name not in LATER_PHASES)
if actual != sorted(p.name for p in EXPECTED):
    err("file-set", f"unexpected extra/missing files: {actual}")
for p in MUST_NOT_EXIST:
    if p.exists():
        err(p.name, "MUST NOT be created in this phase")
print(f"[1] expected file set: {len(texts)}/{len(EXPECTED)} present, no extras")

reg = (ROOT / "docs/60-decisions/decision-register.md").read_text(encoding="utf-8")
REAL_D = set(re.findall(DRX, reg))

trd = (ROOT / "docs/30-technical/trd-v1.0.md").read_text(encoding="utf-8")
REAL_TR = set(re.findall(r"TR-\d+", trd))

# ---- per-document checks ---------------------------------------------
for name, t in texts.items():
    for field in ("**Document**", "**Version**", "**Status**", "**Date**", "**Owner**"):
        if field not in t:
            err(name, f"control block missing {field}")
    if not re.search(r"\|\s*\*\*Version\*\*\s*\|\s*v1\.0\s*\|", t):
        err(name, "Version is not v1.0")
    if not re.search(r"\|\s*\*\*Status\*\*\s*\|\s*Draft\s*\|", t):
        err(name, "Status is not Draft")
    if "## Scope" not in t:
        err(name, "missing ## Scope")
    if "## Authority" not in t:
        err(name, "missing ## Authority")
    if "## Decision references" not in t:
        err(name, "missing ## Decision references")
    else:
        tail = t.split("## Decision references")[-1]
        if re.search(r"^## ", tail, re.M):
            err(name, "## Decision references is not the final section")
    if "Traceability" not in t:
        err(name, "missing traceability section")
    if not t.startswith("# "):
        err(name, "no H1 on first line")
    if not t.endswith("\n") or t.endswith("\n\n"):
        err(name, "bad trailing newline")
    for bad in ("TODO", "TBD", "FIXME", "XXX", "Lorem"):
        if bad in t:
            err(name, f"contains {bad}")
    if not re.search(r"^#+ .*Open items", t, re.M):
        err(name, "no explicit 'Open items' section")

print("[2] control blocks  [3] v1.0/Draft  [4] scope+authority  [5] decision refs"
      "  [6] hygiene  [7] traceability + open items — checked")

# ---- 8 listed D-xx == body-cited D-xx --------------------------------
for name, t in texts.items():
    body, _, decl = t.partition("## Decision references")
    listed = set(re.findall(DRX, decl))
    cited = set(re.findall(DRX, body))
    if listed - cited:
        err(name, f"listed but not cited: {sorted(listed - cited)}")
    if cited - listed:
        err(name, f"cited but not listed: {sorted(cited - listed)}")
print("[8] decision-reference lists match body citations — checked")

# ---- 9 every D-xx is real --------------------------------------------
for name, t in texts.items():
    for d in sorted(set(re.findall(DRX, t))):
        if d not in REAL_D:
            err(name, f"unknown decision {d}")
print(f"[9] all D-xx exist in register ({len(REAL_D)} known) — checked")

# ---- 10 every TR-xxx is real -----------------------------------------
for name, t in texts.items():
    for tr in sorted(set(re.findall(r"TR-\d+", t))):
        if tr not in REAL_TR:
            err(name, f"unknown TRD anchor {tr}")
print(f"[10] all TR-xxx exist in TRD ({len(REAL_TR)} known) — checked")

# ---- 11 forbidden IDs -------------------------------------------------
for name, t in texts.items():
    for bad in ("D-46a", "D-46b"):
        if bad in t:
            err(name, f"forbidden id {bad}")
    for bad in re.findall(r"\bL-(?:1|9|11|13|14)\b", t):
        err(name, f"forbidden legal id {bad}")
print("[11] no D-46a/D-46b, no L-1/9/11/13/14 — checked")

# ---- 12 marker allow-list --------------------------------------------
for name, t in texts.items():
    for m in re.findall(r"Open\s+[—-]\s+[a-z][a-z ]+", t):
        if " ".join(m.split()) not in MARKERS:
            err(name, f"non-allow-listed marker {m.strip()!r}")
print("[12] marker allow-list — checked")

# ---- 13 local IDs defined only in home doc ---------------------------
for name, t in texts.items():
    for p in HOME:
        hits = len(re.findall(rf"^\|\s*\**{p}-[0-9][^|]*\|", t, re.M))
        if hits and HOME[p] != name:
            err(name, f"defines {hits} {p}- IDs but home is {HOME[p]}")
print("[13] local ID ownership — checked")

# ---- 14 prefix collision scan ----------------------------------------
others = [p for p in ROOT.glob("docs/**/*.md")
          if "40-operations" not in str(p) and "45-quality" not in str(p)]
collisions = collections.defaultdict(set)
for p in others:
    ot = p.read_text(encoding="utf-8")
    for pref in HOME:
        if re.search(rf"^\|\s*\**{pref}-[0-9]", ot, re.M):
            collisions[pref].add(p.relative_to(ROOT).as_posix())
for pref, where in sorted(collisions.items()):
    err("prefix-scan", f"{pref}- also defined in {sorted(where)}")
print(f"[14] prefix collision scan over {len(others)} other docs — checked")

# ---- 15 relative links resolve ---------------------------------------
for p in EXPECTED:
    if not p.exists():
        continue
    for target in re.findall(r"\]\((\.\./[^)#]+|\./[^)#]+)\)", p.read_text(encoding="utf-8")):
        if not (p.parent / target).resolve().exists():
            err(p.name, f"broken relative link -> {target}")
print("[15] relative links resolve — checked")

# ---- 16 banned invention scan ----------------------------------------
BANNED = [
    (r"\bETB\s*[0-9]", "a price in Birr"),
    (r"\bBirr\s*[0-9]", "a price in Birr"),
    (r"99\.9\s*%", "an invented availability SLA"),
    (r"\bSLA of\b", "an invented SLA"),
    (r"within\s+\d+\s+(?:business\s+)?(?:hours|days)\b", "an invented response target"),
    (r"\bretained for\s+\d+\s+(?:days|months|years)\b", "an invented retention period"),
    (r"\b\d+\s+(?:Operators|staff members|employees)\b", "an invented staffing number"),
    (r"\bat least\s+\d{2,}\s+(?:listings|businesses)\b", "an invented launch threshold"),
]
for name, t in texts.items():
    for rx, why in BANNED:
        for m in re.findall(rx, t, re.I):
            err(name, f"possible invention ({why}): {m!r}")
print("[16] banned-invention scan — checked")

# ---- 17 the 20-business pilot figure is the only allowed count -------
for name, t in texts.items():
    for m in re.findall(r"\b(\d{2,})\s+business", t):
        if m != "20":
            err(name, f"unexpected business count {m}")
print("[17] pilot figure discipline — checked")

# ---- report -----------------------------------------------------------
print()
if fail:
    print(f"FAIL — {len(fail)} problem(s):")
    for f in fail:
        print("  ✗", f)
    sys.exit(1)
print("PASS — all 17 checks clean")
