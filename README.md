# OneCMS Dev Tools

Set of development tools to analyze and auto fix code standards, formatting and other stuffs for OneCMS packages.

The following libraries are included:

- `friendsofphp/php-cs-fixer`
- `phpstan/phpstan`
- `rectorphp/rector`

## Installation

First add the following to your `composer.json`

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/getonecms/dev-tools"
  }
]
```

Now it can be install via composer

```bash
composer require getonecms/dev-tools --dev
```

## Configuration

- PHP CS Fixer
  
You can add the rules and finder instance in the following way to your config file `.php-cs-fixer.php`:

```php
use OneCMS\Tools\Fixer;
use PhpCsFixer\Finder;

// create a finder instance according to your needs
$finder = Finder::create()->in(__DIR__ . '/src');
// add the rules and it will override the defaults
$rules = [];

return (new Fixer($finder, $rules))->getConfig();
```

- PHPStan

Add `phpstan.neon` in the root directory and you can include `src/phpstan.neon` file like below if you wish but not necessary.

```neon
...
includes:
  - vendor/getonecms/dev-tools/src/phpstan.neon
...
```

- Rector

Add `rector.php` in the root directory and the following, if you need to add more paths you can add them as well:

```php
use OneCMS\Tools\Rector;

// Initialize
return (new Rector())->initialize([
    __DIR__ . '/src',
    __DIR__ . '/test'
]);
```

## Usage

- Analyze your code first with PHPStan static analyzer for errors and fix (manual fix):

```bash
vendor/bin/phpstan analyse [options] [<paths>...]
```

- Run code sniffer and format your codes.

(Recommended) If you wish to fix manually you can just output the rules that will apply like the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --dry-run --diff --show-progress=dots
```

If you wish to auto fix the files and output the summary of changes you can run the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots
```

>***NOTE:** You can also setup this extension with your favorite IDE or editor, so you can able to get the advantage while developing.*

## TODO

- [x] Install `phpstan/phpstan` library.
- [x] Install Rector `rectorphp/rector` library.
- [ ] Use `pre-commit` hook to analyze code for errors using PHPStan and abort commit if found errors.
- [ ] Use `pre-commit` hook to run code sniffer and abort commit if found errors.
- [ ] Add alias commands for the library commands, like the followings:

```bash
# ./vendor/bin/php-cs-fixer fix --verbose --dry-run --diff --show-progress=dots
composer code-sniff

# ./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots
composer code-sniff-fix

# ./vendor/bin/phpstan
composer code-analyze
```

- [ ] Add support to pass arguments to the alias commands.
- [ ] Add prettier php-cs-fixer plugin.
