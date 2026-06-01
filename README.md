# WebifyCMS Dev Tools

Set of development tools to analyze and auto fix code standards, formatting and other stuffs for WebifyCMS packages.

The following libraries are included:

- `friendsofphp/php-cs-fixer`
- `phpstan/phpstan`
- `rector/rector`
- `symfony/var-dumper`

## Installation

Install via composer

```bash
composer require webifycms/dev-tools --dev
```

## Configuration

- PHP CS Fixer
  
You can add the rules and finder instance in the following way to your config file `.php-cs-fixer.php`:

```php
use Webify\Tools\Fixer;
use PhpCsFixer\Finder;

// create a finder instance according to your needs
$finder = Finder::create()->in(__DIR__ . '/src');
// add the rules and it will override the defaults
$rules = [];

return (new Fixer($finder, $rules))->getConfig();
```

- PHPStan

Add `phpstan.neon` in the root directory, and include the default config like below:

```neon
includes:
  - vendor/webifycms/dev-tools/phpstan-default.neon
```

- Rector

Add `rector.php` in the root directory and the following, if you need to add more paths you can add them as well:

```php
use Webify\Tools\Rector;

// Initialize
return (new Rector())
    ->initialize([
        __DIR__ . '/src',
        __DIR__ . '/test'
    ])
    ->withPhpSets(php81: true);
```

## Usage

- Analyze your code first with PHPStan static analyzer for errors and fix (manual fix):

```bash
vendor/bin/phpstan analyse [options] [<paths>...]
```

- Run code sniffer and format your codes.

(Recommended) If you wish to fix manually, you can just output the rules that will apply like the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --diff --show-progress=dots --dry-run
```

If you wish to auto fix the files, and output the summary of changes you can run the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots
```

Upgrade code with RectorPHP

```bash
# to output the changes only
./vendor/bin/rector process --dry-run

# to make the changes
./vendor/bin/rector process
```

## Debugging

The `symfony/var-dumper` package is included and provides the `dump()` and `dd()` helper functions for inspecting
variables during development.

e.g. use `dump()` within your `.php-cs-fixer.php` or `rector.php` config files to inspect the resolved configuration:

```php
use Webify\Tools\Fixer;
use PhpCsFixer\Finder;

$finder = Finder::create()->in(__DIR__ . '/src');
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
vendor/bin/phpunit
```

>***NOTE:** You can also set up this extension with your favorite IDE or editor, so you can get more 
> advantages like format on save while developing.*

## TODO

- [x] Install `phpstan/phpstan` library.
- [x] Install Rector `rector/rector` library.
- [x] Add alias commands for the library commands, like the following:

```bash
# ./vendor/bin/php-cs-fixer fix --verbose --diff --show-progress=dots --dry-run
composer sniff

# ./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots
composer code-format

# ./vendor/bin/phpstan
composer analyse
```

- [x] Add support to pass arguments to the alias commands.
