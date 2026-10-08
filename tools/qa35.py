#!/usr/bin/env python3
"""Phase 3.5 QA verifier — docs/50-security/ and docs/55-privacy/."""
import re, sys, pathlib, collections

DRX = r"(?<![\w-])D-\d{2}[a-z]?(?![\w])"
ROOT = pathlib.Path("/home/user/bulbula.et")
SEC = ROOT / "docs/50-security"
PRIV = ROOT / "docs/55-privacy"

EXPECTED = [
    SEC / "security-architecture-v1.0.md",
    SEC / "threat-model-v1.0.md",
    SEC / "authentication-security-v1.0.md",
    SEC / "application-security-v1.0.md",
    SEC / "security-operations-v1.0.md",
    SEC / "incident-response-v1.0.md",
    PRIV / "privacy-governance-v1.0.md",
    PRIV / "data-inventory-v1.0.md",
    PRIV / "privacy-by-design-v1.0.md",
    PRIV / "data-retention-v1.0.md",
    PRIV / "data-subject-rights-v1.0.md",
    PRIV / "vendor-and-transfer-register-v1.0.md",
    PRIV / "privacy-notice-requirements-v1.0.md",
]

# local ID prefix -> home document
HOME = {
    "SEC": "security-architecture-v1.0.md",
    "SAC": "security-architecture-v1.0.md",
    "THR": "threat-model-v1.0.md",
    "AS":  "authentication-security-v1.0.md",
    "APP": "application-security-v1.0.md",
    "SO":  "security-operations-v1.0.md",
    "IR":  "incident-response-v1.0.md",
    "SEV": "incident-response-v1.0.md",
    "PG":  "privacy-governance-v1.0.md",
    "REG": "privacy-governance-v1.0.md",
    "DI":  "data-inventory-v1.0.md",
    "PBD": "privacy-by-design-v1.0.md",
    "RET": "data-retention-v1.0.md",
    "DSR": "data-subject-rights-v1.0.md",
    "VT":  "vendor-and-transfer-register-v1.0.md",
    "PNR": "privacy-notice-requirements-v1.0.md",
}

MARKERS = [
    "Open — security decision", "Open — privacy decision",
    "Open — product decision", "Open — implementation detail",
    "Open — technical decision", "PENDING COUNSEL", "PENDING PILOT",
]

fail = []
warn = []


def err(doc, msg):
    fail.append(f"{doc}: {msg}")


# ---- load -------------------------------------------------------------
texts = {}
for p in EXPECTED:
    if not p.exists():
        err(p.name, "MISSING FILE")
    else:
        texts[p.name] = p.read_text(encoding="utf-8")

print(f"[1] expected file set: {len(texts)}/{len(EXPECTED)} present")

reg = (ROOT / "docs/60-decisions/decision-register.md").read_text(encoding="utf-8")
REAL_D = set(re.findall(DRX, reg))

trd = (ROOT / "docs/30-technical/trd-v1.0.md").read_text(encoding="utf-8")
REAL_TR = set(re.findall(r"TR-\d+", trd))

# ---- per-document checks ---------------------------------------------
for name, t in texts.items():
    # 2 control block
    for field in ("**Document**", "**Version**", "**Status**", "**Date**", "**Owner**"):
        if field not in t:
            err(name, f"control block missing {field}")
    # 3 version + status
    if not re.search(r"\|\s*\*\*Version\*\*\s*\|\s*v1\.0\s*\|", t):
        err(name, "Version is not v1.0")
    if not re.search(r"\|\s*\*\*Status\*\*\s*\|\s*Draft\s*\|", t):
        err(name, "Status is not Draft")
    # 4 scope + authority
    if "## Scope" not in t:
        err(name, "missing ## Scope")
    if "## Authority" not in t:
        err(name, "missing ## Authority")
    # 5 decision references present and final
    if "## Decision references" not in t:
        err(name, "missing ## Decision references")
    else:
        tail = t.split("## Decision references")[-1]
        if re.search(r"^## ", tail, re.M):
            err(name, "## Decision references is not the final section")
    # 6 H1 + trailing newline + no TODO
    if not t.startswith("# "):
        err(name, "no H1 on first line")
    if not t.endswith("\n") or t.endswith("\n\n"):
        err(name, "bad trailing newline")
    for bad in ("TODO", "TBD", "FIXME", "XXX", "Lorem"):
        if bad in t:
            err(name, f"contains {bad}")
    # 7 unresolved items
    if not re.search(r"Unresolved items|unresolved", t):
        err(name, "no unresolved-items section")

print("[2] control blocks  [3] v1.0/Draft  [4] scope+authority  [5] decision refs  [6] hygiene  [7] unresolved — checked")

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
        err(name, f"forbidden legal id L-{bad}")
print("[11] no D-46a/D-46b, no L-1/9/11/13/14 — checked")

# ---- 12 marker allow-list --------------------------------------------
for name, t in texts.items():
    for m in re.findall(r"Open\s+[—-]\s+[a-z ]+", t):
        norm = m.strip()
        if norm not in MARKERS:
            err(name, f"non-allow-listed marker {norm!r}")
print("[12] marker allow-list — checked")

# ---- 13 local IDs defined only in home doc ---------------------------
defpat = {p: re.compile(rf"^\| {p}-[0-9]") for p in HOME}
for name, t in texts.items():
    for p, rx in defpat.items():
        if rx.search(t) or re.search(rf"^\| \*\*{p}-[0-9]", t, re.M):
            if HOME[p] != name:
                # definitions in a table row outside the home document
                hits = len(re.findall(rf"^\|\s*\**{p}-[0-9][^|]*\|", t, re.M))
                if hits:
                    err(name, f"defines {hits} {p}- IDs but home is {HOME[p]}")
print("[13] local ID ownership — checked")

# ---- 14 prefix collision scan ----------------------------------------
others = [p for p in ROOT.glob("docs/**/*.md")
          if "50-security" not in str(p) and "55-privacy" not in str(p)]
collisions = collections.defaultdict(set)
for p in others:
    ot = p.read_text(encoding="utf-8")
    for pref in HOME:
        if re.search(rf"^\|\s*\**{pref}-[0-9]", ot, re.M):
            collisions[pref].add(p.relative_to(ROOT).as_posix())
for pref, where in sorted(collisions.items()):
    err("prefix-scan", f"{pref}- also defined in {sorted(where)}")
print(f"[14] prefix collision scan over {len(others)} other docs — checked")

# ---- report -----------------------------------------------------------
print()
if fail:
    print(f"FAIL — {len(fail)} problem(s):")
    for f in fail:
        print("  ✗", f)
    sys.exit(1)
print("PASS — all 14 checks clean")
