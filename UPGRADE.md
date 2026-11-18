# Upgrade guide

## From 1.2.x to 1.3.0

This release adds the reusable Whoops error page and moves the shared tool
configuration into `tools/`. The configuration move is breaking, so update the
paths below before upgrading.

1. Update the constraint:

   ```bash
   composer require --dev webifycms/dev-tools:^1.3
   ```

   `filp/whoops` and `squizlabs/php_codesniffer` come with the package, so they do
   not need their own requirements.

### Breaking: tool configs moved into `tools/`

The files shipped by this package moved as follows:

| 1.2.x path | 1.3.0 path |
| --- | --- |
| `vendor/webifycms/dev-tools/phpstan-default.neon` | `vendor/webifycms/dev-tools/tools/phpstan-default.neon` |
| `vendor/webifycms/dev-tools/.php-cs-fixer.dist.php` | `vendor/webifycms/dev-tools/tools/.php-cs-fixer.dist.php` |
| `vendor/webifycms/dev-tools/phpstan.dist.neon` | `vendor/webifycms/dev-tools/tools/phpstan.dist.neon` |
| `vendor/webifycms/dev-tools/phpunit.xml.dist` | `vendor/webifycms/dev-tools/tools/phpunit.xml.dist` |
| `vendor/webifycms/dev-tools/rector.php` | `vendor/webifycms/dev-tools/tools/rector.php` |

If your own configs followed the old root-level layout, move them into `tools/`
as well and adjust their paths:

- PHP-CS-Fixer: build the finder from `dirname(__DIR__)` instead of `__DIR__`.
- PHPStan: include
  `../vendor/webifycms/dev-tools/tools/phpstan-default.neon` and write project
  paths with `%currentWorkingDirectory%`.
- Rector: build paths with `dirname(__DIR__)` instead of `__DIR__`.
- PHPUnit: point `bootstrap`, `cacheDirectory`, testsuites and source at one
  level above the XML file.

```php
// tools/.php-cs-fixer.dist.php
$root   = dirname(__DIR__);
$finder = Finder::create()->in($root . '/src');
```

```neon
# tools/phpstan.dist.neon
includes:
  - ../vendor/webifycms/dev-tools/tools/phpstan-default.neon

parameters:
  paths:
    - %currentWorkingDirectory%/src
    - %currentWorkingDirectory%/test
```

```php
// tools/rector.php
return (new Rector())
    ->initialize([
        dirname(__DIR__) . '/src',
        dirname(__DIR__) . '/test',
    ])
    ->withPhpSets(php84: true);
```

```xml
<!-- tools/phpunit.xml.dist -->
<phpunit bootstrap="../vendor/autoload.php" cacheDirectory="../.phpunit.cache">
    <testsuites>
        <testsuite name="unit">
            <directory>../test</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

The tools no longer find these files at the project root, so each Composer
script must name its config explicitly:

```bash
php-cs-fixer fix --config=tools/.php-cs-fixer.dist.php --dry-run
phpstan analyse -c tools/phpstan.dist.neon
rector process --config=tools/rector.php --dry-run
phpunit -c tools/phpunit.xml.dist
phpcs --standard=vendor/webifycms/dev-tools/tools/phpcs.xml src test
```

Other behavior changes from the same update:

- The shared PHPStan level moved from `8` to `10`. A package that needs a lower
  level must set `level` in its own config.
- `composer code-check` now also runs `phpcs`, which fails on lines longer than
  120 columns. The 120-column check is available on its own as `composer phpcs`.
- The PHP-CS-Fixer defaults now allow a long class declaration to wrap after
  `extends` or `implements` instead of forcing it onto one line.

Packages that only call `Webify\Tools\Fixer` or `Webify\Tools\Rector` from their
own configs need no PHP code changes.

### New: branded Whoops error page

Register the wrapper during development to replace the default Whoops page:

```php
use Webify\Tools\Whoops\Whoops;

(new Whoops())->register();
```

Web requests show the branded page. Command-line runs keep using Whoops'
plain-text output because a terminal cannot read HTML.

To use an application's own brand, pass an image source and its description:

```php
(new Whoops(logo: 'https://example.test/logo.svg', logoAlt: 'Acme'))->register();
```

An empty logo removes the brand from the page:

```php
(new Whoops(logo: ''))->register();
```

The same arguments are available on
`Webify\Tools\Whoops\Handler\WebifyPageHandler` when you construct the handler
yourself. To keep an existing Whoops handler, pass it as the first argument:

```php
(new Whoops($existingHandler))->register();
```

No assets need to be copied into the consuming application. The page, CSS and
logo are read from the package and placed into the rendered HTML.
