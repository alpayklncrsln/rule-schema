# This is my package rule-schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v//rule-schema.svg?style=flat-square)](https://packagist.org/packages//rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status//rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com//rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status//rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com//rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt//rule-schema.svg?style=flat-square)](https://packagist.org/packages//rule-schema)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/rule-schema.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/rule-schema)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require alpayklncrsln/rule-schema
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="rule-schema-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

### Create Rule Schema

```php
$ruleSchema = new Alpayklncrsln\RuleSchema\RuleSchema::create(
    Alpayklncrsln\RuleSchema\Rule::make('name'),
    Alpayklncrsln\RuleSchema\Rule::make('slug'),
    .
    .
)->getRules();
```
Output:
```php
$ruleSchema = [
    'name' => [],
    'slug' => [],
]
```

### Create Rule

```php
$rule = new Alpayklncrsln\RuleSchema\Rule::make('name')->reqired()->max()->getRule();
```

Output:
```php
'name' => ['required','max:255']
```

### Auth Rule Schema
#### Login

```php
\Alpayklncrsln\RuleSchema\RuleSchema::create(
      Alpayklncrsln\RuleSchema\Rule::make('email')->required()->email()->max()->exists('users', 'email'),
      Alpayklncrsln\RuleSchema\Rule::make('password')->required()->min(8)->max(),
)->fetchRules();
```

Or

```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$ruleSchema = DefaultRuleSchema::login()->getRules();
```


#### Register

```php
\Alpayklncrsln\RuleSchema\RuleSchema::create(
      Alpayklncrsln\RuleSchema\Rule::make('name')->required()->max(),
      Alpayklncrsln\RuleSchema\Rule::make('email')->required()->email()->max()->unique('users', 'email'),
      Alpayklncrsln\RuleSchema\Rule::make('password')->required()->min(8)->max()->confirmed(),
)->fetchRules();
```

Or

```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$ruleSchema = DefaultRuleSchema::register()->getRules();
```

## Available Rules

Laravel Validation Docs: [Rules](https://laravel.com/docs/.x/validation#available-validation-rules) all rules are available.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [alpayklncrsln](https://github.com/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
