
# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)  
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)  
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)  
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema is a powerful Laravel package that provides a fluent interface for building validation rules in Laravel applications. It simplifies the process of creating complex validation rules while maintaining clean and readable code. The package includes pre-built validation schemas for common authentication scenarios and supports all Laravel validation rules.

## Features

- Fluent interface for defining validation rules
- Pre-built schemas for authentication scenarios (login, registration, password reset)
- Support for all Laravel validation rules
- Conditional rule application
- Rule merging and modification
- Type-safe rule creation
- Seamless integration with Form Requests
- Clean and maintainable validation logic

## Installation

Install the package via Composer:

```bash
composer require alpayklncrsln/rule-schema
```

## Basic Usage

Here's a simple example of creating validation rules:

##### RuleSchema Methods

- `create` - Creates a new rule schema.
- `when` - Adds rules if a condition is met.
- `expect` - Removes specified rules.
- `merge` - Merges additional rules.
- `existsMerge` - Merges rules if a field exists in the schema.
- `auth` - Adds rules if the user is authenticated.
- `notauth` - Adds rules if the user is not authenticated.
- `putSchema` - Adds rules for PUT requests.
- `postSchema` - Adds rules for POST requests.
- `patchSchema` - Adds rules for PATCH requests.
- `arraySchema` - Adds nested schemas.
- `getRules` - Retrieves the compiled rules.

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)->max(255)
)->getRules();
```

Output:

```php
[
    'email' => ['required', 'email', 'max:255'],
    'password' => ['required', 'min:8', 'max:255'],
]
```

## Integration with Form Request

```php
<?php

namespace App\Http\Requests\Auth;

use Alpayklncrsln\RuleSchema\Interfaces\HasRuleSchema;
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest implements HasRuleSchema
{
    public function authorize(): bool
    {
        return true;
    }
    public function ruleSchema(): RuleSchema
    {
        return RuleSchema::create(
            Rule::make('email')
                ->required()
                ->email(message: "Email geçerli değil.")->max()->min()
                ->exists('users', 'email'),
            Rule::make('password')->required()->min(8)->max()
        );
    }
    public function rules(): array
    {
        return $this->ruleSchema()->getRules();
    }
    public function messages(): array
    {
        return $this->ruleSchema()->getMessages();
    }
}
```

Output:

```php
[
    'email' => ['required', 'email', 'exists:users,email'],
    'password' => ['required', 'min:8']
]
```

## Pre-built Authentication Schemas

The package includes pre-built schemas for common authentication scenarios:

### Login
```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

// Using the pre-built schema
$rules = DefaultRuleSchema::login()->getRules();

// Custom implementation
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->exists('users', 'email'),
    Rule::make('password')->required()->min(8)
)->getRules();
```

### Registration
```php
// Using the pre-built schema
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$rules = DefaultRuleSchema::register()->getRules();

// Custom implementation
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;
$rules = RuleSchema::create(
    Rule::make('name')->required()->max(255),
    Rule::make('email')->required()->email()->unique('users'),
    Rule::make('password')->required()->min(8)->confirmed()
)->getRules();
```

## Advanced Features

### Conditional Rules
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create()
    ->when(Auth::check(), 
        Rule::make('role')->required()->in(['admin', 'user']),
        Rule::make('permissions')->required()->array()
    )
    ->getRules();
```

Output:
```php
[
    'role' => ['required', 'in:admin,user'],
    'permissions' => ['required', 'array']
]
```

### Rule Merging
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$baseRules = RuleSchema::create(
    Rule::make('name')->required()
);

$rules = $baseRules
    ->merge(
        Rule::make('email')->required()->email()
    )
    ->getRules();
```

Output:
```php
[
    'name' => ['required'],
    'email' => ['required', 'email']
]
```

### Excluding Rules
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create(
    Rule::make('name')->required(),
    Rule::make('email')->required()->email(),
    Rule::make('password')->required()
)
->expect('password')
->getRules();
```

Output:
```php
[
    'name' => ['required'],
    'email' => ['required', 'email']
]
```

### MultiStep Rules
Use the `MultiStepSchema` class for step-by-step rule application:
- `step` - Add rules for a specific step.
- `lastStep` - Add rules for the final step.
- `allSteps` - Apply rules to all steps at once.
- `setAllSteps` - Configure step application behavior.
- `getRules` - Retrieve the compiled rules.

```php
use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = MultiStepSchema::make()
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

### MultiStep `AllSteps` Usage
Apply rules to all steps simultaneously:
```php
use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = MultiStepSchema::make('step', 1, 2)
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

### File Handling Rules
Use `mimeAndMimetypes` to define file type rules in a single method:
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\Enums\FileMime;

$rules = RuleSchema::create(
    Rule::make('image')->required()->image()
    ->mimeAndMimetypes(
        mimes: FileMime::JPEG, FileMime::JPG, FileMime::PNG
    )
)
->getRules();
```

Output:
```php
[
    'image' => [
        'required',
        'image',
        'mimes:jpeg,jpg,png',
        'mimetypes:image/jpeg,image/jpg,image/png'
    ]
]
```

## Available Methods

The package supports all Laravel validation rules, including:

- Basic validation (required, string, numeric, etc.)
- Date validation (date, after, before, etc.)
- File validation (file, image, mimes, etc.)
- Size validation (min, max, between, etc.)
- Database validation (unique, exists)
- Custom rules

For a full list of supported rules, refer to the [Laravel Validation Documentation](https://laravel.com/docs/11.x/validation#available-validation-rules).

## Testing

```bash
composer test
```

## Contributing

Please review [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Review our [security policy](../../security/policy) on how to report security vulnerabilities.

## Contributors

- [Alpay Kılınçarslan](https://github.com/alpayklncrsln)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). See the [License File](LICENSE.md) for more information.
```
