# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added

- Professional plain-PHP engineering baseline, derived from
  [benjaminhaeberli/php-skeleton](https://github.com/benjaminhaeberli/php-skeleton):
  - `Bulbula\Foundation\Application` composition root with an `Environment` enum
  - immutable dot-notation `Config` repository loaded from `config/*.php`
  - PSR-3 logging foundation (Monolog): readable lines locally, JSON in production
  - error handling that promotes PHP errors to exceptions, logs uncaught
    throwables and hides internals in production behind a reference id
  - typed `Env` reader and `.env.example` (environment/configuration separation)
- **Infection mutation testing** (`infection.json5`, `composer infection`) gated
  at MSI 100 %.
- PHPInsights configuration that defers formatting to Pint and gates quality,
  architecture, complexity and style.
- CI split into fast pull-request checks (`ci.yml`), non-blocking nightly
  mutation testing (`mutation.yml`) and security scanning (`security.yml`),
  plus Dependabot for Composer and GitHub Actions.
- Pre-launch landing page served from `public/`.

### Changed

- Repository retargeted from a reusable package to an application:
  `composer.lock` is committed, autoloading moved to the `Bulbula\` namespace,
  and the document root lives in `public/`.
- Coding-standards CI no longer auto-commits Pint fixes; it checks and fails
  instead, so pull requests stay reviewable.

### Removed

- Skeleton-specific files: example class and helper, funding configuration,
  the Laravel example workflow, and the Laravel-oriented Ward security scan
  (replaced by `composer audit` and Gitleaks).
- `spatie/ray` — a paid, proprietary desktop debugger that the architecture
  test already forbids using.
