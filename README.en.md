# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema is a powerful Laravel package that provides a fluent interface for creating validation rules in Laravel applications. It simplifies the process of creating complex validation rules while maintaining a clean and readable code structure. The package includes pre-built validation schemas for common authentication scenarios and supports all Laravel validation rules.

## Features

- Fluent interface for creating validation rules
- Pre-built schemas for authentication scenarios (login, registration, password reset)
- Support for all Laravel validation rules
- Conditional rule application
- Rule merging and editing
- Type-safe rule creation
- Easy integration with Form Requests
- Clean and maintainable validation logic

## Installation

You can install the package via composer:

```bash
composer require alpayklncrsln/rule-schema
```

## Basic Usage

Here's a simple example of creating validation rules:

##### RuleSchema Methods

- `create` - Used to create rules.
- `when` - Adds rules to the schema if the condition is met.
- `expect` - Excludes specific rules.
- `merge` - Merges additional rules.
- `existsMerge` - Adds a rule if the field exists in a previously created schema.
- `auth` - Adds rules if the session is active.
- `notauth` - Adds rules if the session is not active.
- `putSchema` - Adds rules for PUT requests.
- `postSchema` - Adds rules for POST requests.
- `patchSchema` - Adds rules for PATCH requests.
- `arraySchema` - Adds nested schemas.
- `getRules` - Retrieves the generated rules.

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

## Pre-built Authentication Schemas

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

### Register
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

### MultiStep Rules
For step-by-step validation, use the `MultiStepSchema` class.
- `step` to apply rules for each step.
- `lastStep` to apply rules for the last step.
- `allSteps` to apply rules across all steps.
- `setAllSteps` to enable or disable applying rules across all steps.
- `getRules` to retrieve the rules.

```php
$rules =\Alpayklncrsln\RuleSchema\Default\MultiStepSchema::make()
    ->step(1,
        Rule::make('name')->required(),
        Rule::make('email')->required()->email(),
    )
    ->step(2,
        Rule::make('password')->required(),
    )
    ->getRules();
```
Output:
```php
// Step 1
[
'name' => ['required'],
'email' => ['required', 'email'],
]
// Step 2
[
'password' => ['required']
]
```

### Using MultiStep `AllSteps`
Apply rules across all steps using `allSteps`:
```php
$rules = \Alpayklncrsln\RuleSchema\Default\MultiStepSchema::make('step', 1, 2)
    ->allSteps()
    ->step(1,
        Rule::make('name')->required(),
        Rule::make('email')->required()->email(),
    )
    ->step(2,
        Rule::make('password')->required(),
    )
    ->getRules();
```
Output:
```php
[
    'step_1.name' => ['required'],
    'step_1.email' => ['required', 'email'],
    'step_2.password' => ['required']
]
```

## Supported Methods

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

If you discover a security vulnerability, please review our [security policy](../../security/policy) for details on reporting.

## Contributors

- [Alpay Kılınçarslan](https://github.com/alpayklncrsln)
- [All Contributors](../../contributors)

## License

MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

