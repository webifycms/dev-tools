# OneCMS Code Sniffer

OneCMS PHP code sniffer tool to automatically fix PHP Coding Standards and it is a wrapper tool for the php-cs-fixer tool.

## Installation

First add the following to your `composer.json`

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/getonecms/sniffer"
  }
]
```

Now it can be install via composer

```bash
composer require getonecms/sniffer --dev
```

## Configuration

You can add the rules and finder instance in the following way to your config file `.php-cs-fixer.php`:

```php
use OneCMS\Sniffer\Fixer;
use PhpCsFixer\Finder;

// create a finder instance according to your needs
$finder = Finder::create()->in(__DIR__ . '/src');
// add the rules and it will override the defaults
$rules = [];

return (new Fixer($finder, $rules))->getConfig();
```

## Usage

You can run the script and pass it's options `./vendor/bin/php-cs-fixer`. See the following examples:

To only output the changes you may run the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --dry-run --diff --show-progress=dots
```

To fix the files and output the changes you may run the following.

```bash
./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots
```
