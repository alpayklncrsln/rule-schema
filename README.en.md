# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema is a powerful Laravel package that provides a fluent interface for building validation rules. It simplifies the process of creating complex validation rules in Laravel applications while maintaining clean and readable code. The package offers pre-built validation schemas for common authentication scenarios and supports all Laravel validation rules.

## Features

- Fluent interface for building validation rules
- Pre-built validation schemas for auth scenarios (login, register, reset password)
- Support for all Laravel validation rules
- Conditional rule application
- Rule merging and manipulation
- Type-safe rule building
- Easy integration with Form Requests
- Clean and maintainable validation logic

## Installation

You can install the package via composer:

```bash
composer require alpayklncrsln/rule-schema
```

## Basic Usage

Here's a simple example of creating validation rules:

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)->max(255)
)->getRules();
```

## Integration with Form Requests

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return RuleSchema::create(
            Rule::make('email')->required()->email()->exists('users', 'email'),
            Rule::make('password')->required()->min(8)
        )->getRules();
    }
}
```

## Pre-built Auth Schemas

The package includes pre-built schemas for common authentication scenarios:

### Login
```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

// Using pre-built schema
$rules = DefaultRuleSchema::login()->getRules();

// Or custom implementation
$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->exists('users', 'email'),
    Rule::make('password')->required()->min(8)
)->getRules();
```

### Registration
```php
// Using pre-built schema
$rules = DefaultRuleSchema::register()->getRules();

// Or custom implementation
$rules = RuleSchema::create(
    Rule::make('name')->required()->max(255),
    Rule::make('email')->required()->email()->unique('users'),
    Rule::make('password')->required()->min(8)->confirmed()
)->getRules();
```

## Advanced Features

### Conditional Rules
```php
$rules = RuleSchema::create()
    ->when(Auth::check(), 
        Rule::make('role')->required()->in(['admin', 'user']),
        Rule::make('permissions')->required()->array()
    )
    ->getRules();
```

### Rule Merging
```php
$baseRules = RuleSchema::create(
    Rule::make('name')->required()
);

$rules = $baseRules
    ->merge(
        Rule::make('email')->required()->email()
    )
    ->getRules();
```

### Excluding Rules
```php
$rules = RuleSchema::create(
    Rule::make('name')->required(),
    Rule::make('email')->required()->email(),
    Rule::make('password')->required()
)
->expect('password')
->getRules();
```

## Available Methods

The package supports all Laravel validation rules, including:

- Basic validation (required, string, numeric, etc.)
- Date validation (date, after, before, etc.)
- File validation (file, image, mimes, etc.)
- Size validation (min, max, between, etc.)
- Database validation (unique, exists)
- Custom validation rules

For a complete list of available rules, refer to the [Laravel Validation Documentation](https://laravel.com/docs/11.x/validation#available-validation-rules).

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alpay Kılınçarslan](https://github.com/alpayklncrsln)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 
