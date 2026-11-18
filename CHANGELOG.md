# Changelog

All notable changes to `webifycms/dev-tools` are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
Release tags use `vMAJOR.MINOR.PATCH`.

## [1.3.0] - 2026-11-18

### Added

- `Webify\Tools\Whoops\Whoops`, a reusable wrapper around `filp/whoops`. Web
  requests get the branded error page; command-line runs get Whoops' plain-text
  handler.
- `Webify\Tools\Whoops\Handler\WebifyPageHandler`, which renders the error,
  earlier errors, stack frames with source snippets, and request values with
  secret-looking names hidden.
- A branded page layout in `templates/whoops/error.html.php` and its stylesheet
  in `assets/whoops.css`. Both are read from the package, so consuming
  applications do not need to publish assets.
- A bundled WebifyCMS logo in `assets/logo.png`. It is the default brand but can
  be replaced with an application's own image source or hidden with `logo: ''`.
  `logoAlt` sets the text for readers who cannot see the image.
- The `filp/whoops` `^2.19` dependency.
- `tools/phpcs.xml` and a `composer phpcs` script that fail on lines longer than
  120 columns. `composer code-check` now runs this check too.
- Unit tests for the wrapper and the page handler.

### Changed

- **BREAKING:** the shared tool configs moved from the package root into
  `tools/`. Config includes, Composer scripts and any copied configs must use the
  new paths. See [UPGRADE.md](UPGRADE.md).
- The shared PHPStan level moved from `8` to `10`.
- PHP-CS-Fixer defaults now allow a long class declaration to wrap after
  `extends` or `implements`.
- `README.md` documents the `tools/` layout, Whoops setup and logo options.
- `AGENTS.md` describes the new package layout and Whoops usage.
- `tools/phpunit.xml.dist` carries the package license header.

[Unreleased]: https://github.com/webifycms/dev-tools/compare/v1.2.1...HEAD
[1.3.0]: https://github.com/webifycms/dev-tools/compare/v1.2.1...v1.3.0
