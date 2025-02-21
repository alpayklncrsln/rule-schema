# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema is a powerful Laravel package that provides a fluent interface for creating validation rules in Laravel applications. It simplifies the process of creating complex validation rules while maintaining clean and readable code structure. The package offers pre-prepared validation schemas for common authentication scenarios and supports all Laravel validation rules.

## Features

- Fluent interface for validation rules
- Ready-made schemas for authentication scenarios (login, registration, password reset)
- Support for all Laravel validation rules
- Conditional rule application
- Rule merging and editing
- Type-safe rule creation
- Easy integration with Form Requests
- Clean and maintainable validation logic
- Package-specific Form request class generation

## Installation

You can install the package via composer:

```bash
composer require alpayklncrsln/rule-schema
```


````aiignore
php artisan rule-schema:auth all
php artisan rule-schema:auth login
php artisan rule-schema:auth register
php artisan rule-schema:auth reset-password
php artisan rule-schema:auth update-password

or

php artisan rule-schema:auth:all
php artisan rule-schema:auth:login
php artisan rule-schema:auth:register
php artisan rule-schema:auth:reset-password
php artisan rule-schema:auth:update-password
````

To Add the Package's Form Request:
```bash
php artisan make:rule-schema Product --m
```

Create your Rule Schema Form Request:

```php
<?php

namespace App\Http\Requests;

use Alpayklncrsln\RuleSchema\RuleSchemaRequest;

class ProductRequest extends RuleSchemaRequest
{
    public function ruleSchema(): RuleSchema
    {
        return RuleSchema::create([
            
        ]);
    }
}
```

## Basic Usage

Here's a simple example of creating validation rules:
##### RuleSchema Methods

- `create` - Used to create rules
- `when` - Used to add rules to the schema if the condition is met
- `expect` - Used to remove required rules
- `merge` - Used for merging rules
- `existsMerge` - Used to add rules if the field exists in a previously created schema
- `auth` - Used to add rules if the session is active
- `notauth` - Used to add rules if the session is not active
- `putSchema` - Used to add rules if the request method is PUT
- `postSchema` - Used to add rules if the request method is POST
- `patchSchema` - Used to add rules if the request method is PATCH
- `arraySchema` - Used to add nested schemas
- `getRules` - Used to retrieve created rules

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

## Form Request Integration

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
                ->email()->max()->min()
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

## Ready Authentication Schemas

The package includes ready-made schemas for common authentication scenarios:

### Login
```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

// Using ready schema
$rules = DefaultRuleSchema::login()->getRules();

// Or custom implementation
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->exists('users', 'email'),
    Rule::make('password')->required()->min(8)
)->getRules();
```

### Register
```php
// Using ready schema
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$rules = DefaultRuleSchema::register()->getRules();

// Or custom implementation
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

### Using `arraySchema` with RuleSchema
- With the `arraySchema` method, you can define nested rules.
- The `isMultiple` variable determines if it's multiple.
- The `methods` variable determines request methods. It specifies when they will be added.
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create([
    Rule::make('name')->required(),
    Rule::make('email')->required()->email(),
    Rule::make('password')->required()->min(8),
])->arraySchema('roles',[
    Rule::make('name')->required()->string()->max()
    ],isMultiple: true )
->getRules();
```

Output:

```php   
[
    'name' => ['required'],
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],  
    'roles.*.name' => ['required', 'string', 'max:255'], 
]
```

### MultiStep Rules
You can use the `MultiStepSchema` class for step-by-step rule application.
- With the `step` method, you can apply rules to each step.
- With the `lastStep` method, you can apply rules to the last step.
- With the `allSteps` method, you can apply all steps at once.
- With the `setAllSteps` method, you can set the application status for all steps.
- With the `getRules` method, you can retrieve the rules.

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
With AllSteps, you can apply all steps at once.
```php
use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use  Alpayklncrsln\RuleSchema\Rule;
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

## File-Related Rules
With the `mimeAndMimetypes` method, you can specify file types.
You can specify both mimes and mimetypes with a single function.
```php
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;
use \Alpayklncrsln\RuleSchema\Enums\FileMime;

$rules = RuleSchema::create(
    Rule::make('image')->required()->image()
    ->mimeAndMimetypes(
    mimes: FileMime::JPEG,FileMime::JPG,FileMime::PNG),
)
->getRules();
```
Output:

```php
[
    'image' => ['required', 'image',
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
- Custom validation rules

For a complete list of available rules, refer to the [Laravel Validation Documentation](https://laravel.com/docs/11.x/validation#available-validation-rules).

## Testing

```bash
composer test
```

## Contributing

Please review the [CONTRIBUTING](CONTRIBUTING.md) file for details.

## Security Vulnerabilities

To learn how to report security vulnerabilities, please review our [security policy](../../security/policy).

## Contributors

- [Alpay Kılınçarslan](https://github.com/alpayklncrsln)
- [All Contributors](../../contributors)

## License

MIT License (MIT). Please see [License File](LICENSE.md) for more information.
