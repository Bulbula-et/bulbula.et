# Contributing

## Setup

```bash
composer install
cp .env.example .env
```

A coverage driver (`pcov` or Xdebug) is required for `composer pest:coverage`
and `composer infection`.

## Before opening a pull request

```bash
composer test        # composer validate, rector, pint, phpstan, pest, type coverage
```

`composer test` is exactly what the `CI ⚡` workflow runs, so a green local run
means a green pull request. Mutation testing is slower and runs on `main` and
nightly; run it locally when you touch `src/`:

```bash
composer infection
```

## Rules

1. **No application frameworks.** Plain PHP only — the architecture test in
   `tests/Arch/ArchTest.php` enforces it.
2. **Everything typed.** PHPStan `level: max` and 100 % type coverage.
3. **Test behaviour, not lines.** A surviving mutant means a missing
   assertion. Fix the test rather than the threshold.
4. **Never weaken a gate to go green.** If a rule is genuinely wrong, change
   it deliberately in its own commit with a reason.
5. **No secrets.** `.env` stays local; `.env.example` holds placeholders only.

## Tests

- `tests/Unit` — classic PHPUnit classes. Infection mutation-tests this suite,
  so new `src/` logic belongs here.
- `tests/Feature` — Pest-style behavioural tests.
- `tests/Arch` — Pest architecture rules.

## Style

Formatting is owned by Pint:

```bash
composer pint
```

PHPInsights deliberately defers to Pint on formatting (see `phpinsights.php`)
and only judges quality, architecture and complexity.

## Commits

Write meaningful commits and keep the history coherent; rebase instead of
merging `main` into your branch.
