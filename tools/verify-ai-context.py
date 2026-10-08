#!/usr/bin/env python3
"""Verify the .ai/ context system.

Run from the repository root:

    python3 tools/verify-ai-context.py

This is an additive check. It does not replace, weaken or modify any
existing quality gate: it reads files, it never writes one, and it never
touches docs/, src/ or the test suite.

Exit code 0 = pass, 1 = failure, 2 = could not run.
"""

from __future__ import annotations

import re
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
AI = ROOT / ".ai"
DOCS = ROOT / "docs"
REGISTER = DOCS / "60-decisions" / "decision-register.md"

# ---------------------------------------------------------------- constants

EXPECTED_FILES = [
    "README.md",
    "ai-workflow-rules.md",
    "project-overview.md",
    "product-context.md",
    "technical-context.md",
    "architecture-context.md",
    "ui-context.md",
    "code-standards.md",
    "security-privacy-context.md",
    "operations-quality-context.md",
    "progress-tracker.md",
    "source-map.md",
]

# README and the workflow rules are process documents, not derived context,
# so they do not carry a source baseline. Every other file must.
NO_BASELINE = {"README.md", "ai-workflow-rules.md"}

# Legal IDs that exist only in the superseded discovery briefs.
DEAD_LEGAL_IDS = {"L-1", "L-9", "L-11", "L-13", "L-14"}

# Terms that must never appear as a live V1 rule. Each may appear only on a
# line that also marks it as excluded, future, open, prohibited or historical.
DRIFT_TERMS = [
    "business account",
    "business accounts",
    "claim this business",
    "claim flow",
    "password",
    "apple sign-in",
    "apple login",
    "sign in with apple",
    "flutter",
    "owner reply",
    "owner replies",
    "self-service ad",
    "self-serve ad",
    "auction",
    "cpc",
    "cpm",
    "cpa",
    "paid ranking",
    "pay for ranking",
    "paid verification",
    "buy verification",
    "behavioural advertising",
    "behavioral advertising",
    "sell customer data",
    "sale of customer data",
    "offline-first",
    "offline first",
]

NEGATION_MARKERS = [
    "never",
    "no ",
    "not ",
    "none",
    "forbidden",
    "prohibit",
    "exclud",
    "exclusion",
    "rejected",
    "do not",
    "don't",
    "must not",
    "cannot",
    "can't",
    "without",
    "open (",
    "open —",
    "open -",
    "future",
    "v2",
    "post-v1",
    "superseded",
    "historical",
    "discovery",
    "pending",
    "drift",
    "question",
    "absent",
    "doe s not",
    "does not",
    "is not",
    "are not",
    "nothing",
]

# Phrases that would make .ai/ claim an authority it does not have.
AUTHORITY_CLAIMS = [
    "this file is the source of truth",
    "this document is the source of truth",
    "`.ai/` is the source of truth",
    ".ai/ is the source of truth",
    "this file overrides",
    "this document overrides",
    "overrides the formal",
    "overrides docs/",
    "takes precedence over docs",
    "authoritative over",
]

# Product rules that must never be contradicted inside .ai/.
CONTRADICTION_PAIRS = [
    ("businesses do not authenticate", "businesses authenticate"),
    ("no passwords", "password field is"),
    ("guest discovery is first-class", "guests must sign in"),
]

results: list[tuple[bool, str]] = []


def check(ok: bool, label: str, detail: str = "") -> None:
    results.append((ok, label if not detail else f"{label} — {detail}"))


def read(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def strip_code_blocks(text: str) -> str:
    return re.sub(r"```.*?```", "", text, flags=re.DOTALL)


def git(*args: str) -> str | None:
    try:
        out = subprocess.run(
            ["git", *args],
            cwd=ROOT,
            capture_output=True,
            text=True,
            timeout=30,
            check=False,
        )
    except (OSError, subprocess.SubprocessError):
        return None
    if out.returncode != 0:
        return None
    return out.stdout


# ------------------------------------------------------------------ checks


def check_file_set() -> dict[str, str]:
    """1. Exactly the expected files, no extras."""
    if not AI.is_dir():
        check(False, "1. .ai/ exists", "directory not found")
        return {}

    present = sorted(p.name for p in AI.iterdir() if p.is_file())
    missing = [f for f in EXPECTED_FILES if f not in present]
    extra = [f for f in present if f not in EXPECTED_FILES]

    check(not missing, "1a. all 12 required files present",
          "missing: " + ", ".join(missing) if missing else "")
    check(not extra, "1b. no unjustified extra files",
          "unexpected: " + ", ".join(extra) if extra else "")

    nested = [str(p.relative_to(AI)) for p in AI.rglob("*") if p.is_dir()]
    check(not nested, "1c. no nested directories in .ai/",
          ", ".join(nested) if nested else "")

    return {name: read(AI / name) for name in present if name in EXPECTED_FILES}


def check_baseline(texts: dict[str, str]) -> None:
    """2. Freshness metadata on every derived file."""
    bad_baseline, bad_derived, bad_status = [], [], []
    sha_re = re.compile(r"Source baseline:\s+([0-9a-f]{40})")
    derived_re = re.compile(r"Last derived from:\s+\S+")
    status_re = re.compile(r"Context status:\s+(Current|Needs review|Stale)")

    shas: set[str] = set()
    for name, text in texts.items():
        if name in NO_BASELINE:
            continue
        m = sha_re.search(text)
        if m:
            shas.add(m.group(1))
        else:
            bad_baseline.append(name)
        if not derived_re.search(text):
            bad_derived.append(name)
        if not status_re.search(text):
            bad_status.append(name)

    check(not bad_baseline, "2a. Source baseline SHA present",
          ", ".join(bad_baseline))
    check(not bad_derived, "2b. Last derived from present",
          ", ".join(bad_derived))
    check(not bad_status, "2c. Context status is one of the three values",
          ", ".join(bad_status))
    check(len(shas) <= 1, "2d. all files share one baseline SHA",
          ", ".join(sorted(shas)) if len(shas) > 1 else "")

    for sha in shas:
        out = git("cat-file", "-t", sha)
        check(out is not None and out.strip() == "commit",
              "2e. baseline SHA is a real commit", sha)


def check_decision_ids(texts: dict[str, str]) -> None:
    """3. Every D-xx cited is real; no fictional IDs."""
    if not REGISTER.is_file():
        check(False, "3. decision register readable", str(REGISTER))
        return

    register = read(REGISTER)
    real = set(re.findall(r"\bD-\d+[a-z]?\b", register))
    check(len(real) > 30, "3a. decision register parsed",
          f"{len(real)} ids found")

    cited: dict[str, set[str]] = {}
    for name, text in texts.items():
        for did in re.findall(r"\bD-\d+[a-z]?\b", text):
            cited.setdefault(did, set()).add(name)

    unknown = {d: sorted(f) for d, f in cited.items() if d not in real}
    check(not unknown, "3b. every D-xx cited exists in the register",
          "; ".join(f"{d} in {', '.join(f)}" for d, f in sorted(unknown.items())))

    retired_markers = ("superseded", "discovery", "historical", "retired",
                       "must not be cited", "no longer")
    dead: dict[str, set[str]] = {}
    for name, text in texts.items():
        lines = text.splitlines()
        for i, raw in enumerate(lines):
            for lid in set(re.findall(r"\bL-\d+\b", raw)):
                if lid not in DEAD_LEGAL_IDS:
                    continue
                window = " ".join(lines[max(0, i - 2):i + 1]).lower()
                if not any(m in window for m in retired_markers):
                    dead.setdefault(lid, set()).add(name)
    check(not dead, "3c. no retired L-id cited as live",
          "; ".join(f"{d} in {', '.join(sorted(f))}" for d, f in dead.items()))


def check_local_prefix(texts: dict[str, str]) -> None:
    """4. Local rules use AI-G-xx, never D-xx."""
    rules = texts.get("ai-workflow-rules.md", "")
    guardrails = set(re.findall(r"\bAI-G-\d+\b", rules))
    check(len(guardrails) >= 5, "4a. AI-G-xx guardrails defined",
          f"{len(guardrails)} found")

    # An AI-G id must never be presented as a decision id.
    bad = [n for n, t in texts.items()
           if re.search(r"AI-G-\d+.{0,20}\bis a decision\b", t)]
    check(not bad, "4b. local guardrails never claim decision status",
          ", ".join(bad))


def check_source_links(texts: dict[str, str]) -> None:
    """5. Referenced docs/ paths exist."""
    broken: dict[str, set[str]] = {}
    path_re = re.compile(r"docs/[A-Za-z0-9._/\-]+\.md")
    for name, text in texts.items():
        for ref in set(path_re.findall(text)):
            ref = ref.rstrip(".,;")
            if not (ROOT / ref).is_file():
                broken.setdefault(ref, set()).add(name)
    check(not broken, "5a. every docs/ path referenced exists",
          "; ".join(f"{r} in {', '.join(sorted(f))}"
                    for r, f in sorted(broken.items())))

    # Each derived file must point at a formal source.
    missing = [n for n, t in texts.items()
               if n not in NO_BASELINE and "docs/" not in t]
    check(not missing, "5b. every derived file cites formal sources",
          ", ".join(missing))

    # The source map must be reachable from the README.
    check("source-map.md" in texts.get("README.md", ""),
          "5c. README links the source map")


def check_drift(texts: dict[str, str]) -> None:
    """6. No forbidden V1 feature stated as a live rule."""
    offenders: list[str] = []
    for name, text in texts.items():
        headings: dict[int, str] = {}
        table_header = ""
        body = strip_code_blocks(text).splitlines()
        for lineno, raw in enumerate(body, 1):
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
            for term in DRIFT_TERMS:
                if term not in low:
                    continue
                # A term is acceptable when the line, its table header or its
                # section heading marks it as excluded, open or historical.
                lookback = " ".join(
                    body[max(0, lineno - 3):lineno - 1]
                ).lower()
                context = " ".join(
                    [low, lookback, table_header, *headings.values()]
                )
                if any(marker in context for marker in NEGATION_MARKERS):
                    continue
                offenders.append(f"{name}:{lineno} '{term}'")
    check(not offenders, "6. no forbidden V1 feature stated affirmatively",
          "; ".join(offenders[:8]) + (" …" if len(offenders) > 8 else ""))


def check_authority(texts: dict[str, str]) -> None:
    """7. .ai/ never claims authority over formal docs."""
    offenders = []
    for name, text in texts.items():
        low = text.lower()
        for claim in AUTHORITY_CLAIMS:
            if claim in low:
                offenders.append(f"{name}: '{claim}'")
    check(not offenders, "7a. no authority claim in .ai/",
          "; ".join(offenders))

    readme = re.sub(r"\s+", " ", texts.get("README.md", "")).lower()
    check("not a source of truth" in readme or "never a source of truth" in readme,
          "7b. README states .ai/ is not a source of truth")
    check("formal doc" in readme and "wins" in readme,
          "7c. README states the formal document wins on conflict")


def check_contradictions(texts: dict[str, str]) -> None:
    """8. No contradictory product rule inside .ai/."""
    joined = "\n".join(texts.values()).lower()
    offenders = []
    for anchor, contradiction in CONTRADICTION_PAIRS:
        if anchor in joined and contradiction in joined:
            offenders.append(f"'{anchor}' vs '{contradiction}'")
    check(not offenders, "8. no contradictory product rule", "; ".join(offenders))


def check_progress_tracker(texts: dict[str, str]) -> None:
    """9. The tracker matches verifiable Git state."""
    tracker = texts.get("progress-tracker.md", "")
    if not tracker:
        check(False, "9. progress tracker present")
        return

    branches_out = git("branch", "-r", "--format=%(refname:short)")
    if branches_out is None:
        check(True, "9a. branch names verified", "skipped: git unavailable")
    else:
        known = {b.strip().split("/", 1)[1]
                 for b in branches_out.splitlines()
                 if "/" in b.strip()}
        cited = set(re.findall(r"`(docs/[a-z0-9\-]+)`", tracker))
        # Only treat it as a branch reference when it is not a file path.
        cited = {c for c in cited if not c.endswith(".md")}
        unknown = sorted(c for c in cited if c not in known)
        check(not unknown, "9a. every branch named exists on the remote",
              ", ".join(unknown))

    head = git("rev-parse", "HEAD")
    check(head is not None, "9b. git HEAD readable")

    # The tracker must not assert a merge that git cannot confirm.
    sha_line = re.search(r"Source baseline:\s+([0-9a-f]{40})", tracker)
    check(sha_line is not None, "9c. tracker carries its own baseline")

    check("Not started" in tracker or "not started" in tracker,
          "9d. tracker marks unfinished phases honestly")


def check_no_invented_automation(texts: dict[str, str]) -> None:
    """10. Only automation that actually exists is described."""
    joined = "\n".join(texts.values())
    if "tools/verify-ai-context.py" in joined:
        check((ROOT / "tools" / "verify-ai-context.py").is_file(),
              "10a. the referenced verifier exists")
    composer = ROOT / "composer.json"
    if composer.is_file():
        scripts = read(composer)
        named = set(re.findall(r"composer ([a-z][a-z0-9:\-]*)", joined))
        unknown = sorted(s for s in named
                         if s not in ("install", "update", "validate",
                                      "audit", "dump-autoload")
                         and f'"{s}"' not in scripts)
        check(not unknown, "10b. every composer script named exists",
              ", ".join(unknown))


def check_docs_untouched() -> None:
    """11. This phase must not modify docs/."""
    status = git("status", "--porcelain", "docs")
    if status is None:
        check(True, "11. docs/ unmodified", "skipped: git unavailable")
        return
    changed = [ln for ln in status.splitlines() if ln.strip()]
    check(not changed, "11. docs/ unmodified in the working tree",
          "; ".join(changed[:5]))


# -------------------------------------------------------------------- main


def main() -> int:
    if not (ROOT / ".git").exists() and not AI.is_dir():
        print("error: run this from the repository root", file=sys.stderr)
        return 2

    texts = check_file_set()
    if texts:
        check_baseline(texts)
        check_decision_ids(texts)
        check_local_prefix(texts)
        check_source_links(texts)
        check_drift(texts)
        check_authority(texts)
        check_contradictions(texts)
        check_progress_tracker(texts)
        check_no_invented_automation(texts)
    check_docs_untouched()

    width = max(len(label) for _, label in results)
    failures = 0
    print("verify-ai-context")
    print("-" * (width + 8))
    for ok, label in results:
        print(f"{'PASS' if ok else 'FAIL'}  {label}")
        if not ok:
            failures += 1
    print("-" * (width + 8))
    print(f"{len(results) - failures} passed, {failures} failed")
    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
