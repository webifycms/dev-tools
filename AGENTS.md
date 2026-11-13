# WebifyCMS Dev Tools

Package `webifycms/dev-tools` (composer type `webifycms-tool`). Shared development
tooling for every WebifyCMS package: PHP-CS-Fixer, PHPStan, Rector, `symfony/var-dumper`.

## Layout

```
src/Fixer/Fixer.php             # (new Fixer($finder, $rules))->getConfig()
src/Fixer/DefaultRules.php      # default CS rules
src/Rector/Rector.php           # (new Rector())->initialize([...])->withPhpSets(...)
tools/phpstan-default.neon      # shared PHPStan includes, level 10
tools/phpcs.xml                 # shared 120-column check (PHP_CodeSniffer)
tools/phpstan.dist.neon         # this package's own PHPStan config
tools/phpunit.xml.dist          # this package's own PHPUnit config
tools/.php-cs-fixer.dist.php    # this package's own CS config
tools/rector.php                # this package's own Rector config
```

Every package keeps its tool config in `tools/`. The tools only auto-discover
configs at the project root, so each command names the file with `--config` or
`-c`. Paths inside a config resolve relative to the config file, so PHP files use
`dirname(__DIR__)`, NEON uses `%currentWorkingDirectory%`, and the PHPUnit XML
uses `../`.

## Usage in other packages

- `tools/.php-cs-fixer.dist.php` → `use Webify\Tools\Fixer; return (new Fixer($finder, $rules))->getConfig();`
- `tools/phpstan.dist.neon` → `includes: [../vendor/webifycms/dev-tools/tools/phpstan-default.neon]`
- `tools/rector.php` → `use Webify\Tools\Rector; return (new Rector())->initialize([...]);`
- `composer phpcs` →
  `phpcs --standard=vendor/webifycms/dev-tools/tools/phpcs.xml src test`.
  The paths must be passed on the command line: the ruleset is loaded from another
  package's `vendor/` folder, so a path written inside it would resolve there.

## Verification

```bash
composer sniff            # CS dry-run
composer analyse          # PHPStan
composer phpcs            # 120-column check
composer rector           # Rector dry-run
composer rector-process   # apply Rector
composer test
composer code-check
composer code-format
```

`composer phpcs` is the only width check; PHP-CS-Fixer has no line-length rule and
cannot reflow code. It reports lines longer than 120 columns as errors, counting a
tab as four columns. Long comments holding an unbreakable URL are left alone, which
is PHPCS's own exception.

## Rules that let a long class declaration wrap

A class whose name, parent and interfaces do not fit in 120 columns keeps the
parent list on the lines below:

```php
final class InvalidCollectionItemTypeException extends InvalidArgumentException implements
	TranslatableExceptionInterface
{
```

Two rules in `DefaultRules` allow this, because the formatter would otherwise pull
it back onto one line:

- `class_definition` with `single_line` off, so the header is not collapsed.
- `single_space_around_construct` without `extends` and `implements`, so a line
  break after either word is not turned into a space. The rest of its list is the
  formatter's own default.

Note that `Fixer` merges a package's options into the defaults per rule, so a
package that names `multi_line_extends_each_single_line` keeps both settings above.
