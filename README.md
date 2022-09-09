# OneCMS Code Sniffer

OneCMS PHP code sniffer tool to automatically fix PHP Coding Standards.

## Installation

First add the following to your `composer.json`

```json
...
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/getonecms/sniffer"
  }
]
...
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

Run the following command to output the rules that can be applied.

```bash
composer sniff
```

Run the following command in order to format the files.

```bash
composer format
```
