# WebifyCMS Dev Tools

Set of development tools to analyze and auto-fix code standards, formatting and other stuff for WebifyCMS packages.

The following libraries are included:

- `friendsofphp/php-cs-fixer`
- `phpstan/phpstan`
- `rector/rector`
- `squizlabs/php_codesniffer`
- `symfony/var-dumper`
- `filp/whoops`

## Installation

Install via composer

```bash
composer require webifycms/dev-tools --dev
```

## Release notes

- [Changelog](CHANGELOG.md)
- [Upgrade guide](UPGRADE.md)

## Configuration

Every package keeps its tool configuration in a `tools/` folder. The tools do not
look there on their own, so each command passes the file explicitly; that is why
every composer script below uses `--config` or `-c`.

- PHP CS Fixer

Put `.php-cs-fixer.dist.php` in `tools/` and build the finder from the package
root, which is one level above:

```php
use Webify\Tools\Fixer;
use PhpCsFixer\Finder;

$root = dirname(__DIR__);

// create a finder instance according to your needs
$finder = Finder::create()->in($root . '/src');
// add the rules and it will override the defaults
$rules = [];

return (new Fixer($finder, $rules))->getConfig();
```

- PHPStan

Put `phpstan.dist.neon` in `tools/` and include the default config. Paths inside a
NEON file resolve relative to the file that declares them, so the project's own
folders are written with `%currentWorkingDirectory%`:

```neon
includes:
  - ../vendor/webifycms/dev-tools/tools/phpstan-default.neon

parameters:
  paths:
    - %currentWorkingDirectory%/src
    - %currentWorkingDirectory%/test
```

- Rector

Put `rector.php` in `tools/` and build the paths from the package root:

```php
use Webify\Tools\Rector;

// Initialize
return (new Rector())
    ->initialize([
        dirname(__DIR__) . '/src',
        dirname(__DIR__) . '/test'
    ])
    ->withPhpSets(php84: true);
```

- PHPUnit

Put `phpunit.xml.dist` in `tools/` and point its paths one level up, because
PHPUnit resolves them relative to the XML file:

```xml
<phpunit bootstrap="../vendor/autoload.php" cacheDirectory="../.phpunit.cache">
    <testsuites>
        <testsuite name="unit">
            <directory>../test/Unit</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

- PHP CodeSniffer

The shared ruleset at `tools/phpcs.xml` checks one thing only: no line may run past
120 columns. It is not a style standard, and it does not replace PHP-CS-Fixer, which
cannot reflow code. A tab counts as four columns, so the reported width matches what an
editor shows. Long comments holding an unbreakable URL are left alone, which is PHPCS's
own exception.

Pass the paths you want checked on the command line. The ruleset is loaded from inside
this package's `vendor/` folder, so a path written inside it would resolve against that
folder instead of your project.

```bash
vendor/bin/phpcs --standard=vendor/webifycms/dev-tools/tools/phpcs.xml src test
```

A class whose name, parent and interfaces do not fit in 120 columns may keep the
parent list on the lines below. The default rules allow that: `class_definition`
does not collapse the header, and `single_space_around_construct` does not turn a
line break after `extends` or `implements` into a space.

## Usage

- Analyze your code first with PHPStan static analyzer for errors and fix (manual fix):

```bash
vendor/bin/phpstan analyse -c tools/phpstan.dist.neon [options] [<paths>...]
```

Additionally, add the following to your `phpstan.neon` file to enable installed PHPStan extensions:

```neon
includes:
    - vendor/phpstan/phpstan-strict-rules/rules.neon
    - vendor/phpstan/phpstan-phpunit/extension.neon
    - vendor/phpstan/phpstan-deprecation-rules/rules.neon
```

- Run code sniffer and format your codes.

(Recommended) If you wish to fix manually, you can just output the rules that will apply like the following.

```bash
./vendor/bin/php-cs-fixer fix --config=tools/.php-cs-fixer.dist.php --verbose --diff --show-progress=dots --dry-run
```

If you wish to auto-fix the files and output the summary of changes, you can run the following.

```bash
./vendor/bin/php-cs-fixer fix --config=tools/.php-cs-fixer.dist.php --verbose --show-progress=dots
```

Upgrade code with RectorPHP

```bash
# to output the changes only
./vendor/bin/rector process --config=tools/rector.php --dry-run

# to make the changes
./vendor/bin/rector process --config=tools/rector.php
```

Check the 120-column limit

```bash
./vendor/bin/phpcs --standard=vendor/webifycms/dev-tools/tools/phpcs.xml src test
```

## Debugging

The `filp/whoops` package is included behind a WebifyCMS wrapper. In a package
that has `webifycms/dev-tools` installed for development, one call turns on the
branded error page:

```php
use Webify\Tools\Whoops\Whoops;

(new Whoops())->register();
```

Web requests get the branded page, which shows the error, stack frames and
request values. Command-line runs get Whoops' plain-text page instead, because
a terminal cannot read HTML. Pass a handler to `new Whoops($handler)` to replace
the default, or use `getRun()` for extra Whoops settings.

The bundled WebifyCMS logo is only the default. An application can supply its
own image source or remove the brand:

```php
use Webify\Tools\Whoops\Whoops;

(new Whoops(logo: ''))->register(); // no logo
(new Whoops(logo: 'https://example.test/logo.svg', logoAlt: 'Acme'))->register();
```

The stylesheet lives in `assets/`; the full page layout lives in
`templates/whoops/error.html.php`. A package can use
`Webify\Tools\Whoops\Handler\WebifyPageHandler` on its own when it only needs the
page, for example to render one caught error. That handler accepts the same
`logo` and `logoAlt` arguments.

The `symfony/var-dumper` package is included and provides the `dump()` and `dd()` helper functions for inspecting
variables during development.

e.g. use `dump()` within your `tools/.php-cs-fixer.dist.php` or `tools/rector.php` config files to inspect the resolved configuration:

```php
use Webify\Tools\Fixer;
use PhpCsFixer\Finder;

$root = dirname(__DIR__);
$finder = Finder::create()->in($root . '/src');
$config = (new Fixer($finder))->getConfig();

// Inspect the merged rules before returning
dump($config->getRules());

return $config;
```

e.g. use `dd()` function (dump and die) to halt execution after dumping:

```php
$config = (new Fixer($finder))->getConfig();
dd($config->getRules());
```

## Testing

Run unit tests with PHPUnit:

```bash
vendor/bin/phpunit -c tools/phpunit.xml.dist
```

>***NOTE:** You can also set up this extension with your favorite IDE or editor, so you can get more 
> advantages like format on save while developing.*
