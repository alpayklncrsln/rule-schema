# This is my package rule-schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v//rule-schema.svg?style=flat-square)](https://packagist.org/packages//rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status//rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com//rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status//rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com//rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt//rule-schema.svg?style=flat-square)](https://packagist.org/packages//rule-schema)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require alpayklncrsln/rule-schema
```

## Usage

### Example Form Request
```php

<?php

namespace App\Http\Requests\Auth;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return RuleSchema::create(
            Rule::make('email')->required()->email()->max()->min()->exists('users', 'email'),
            Rule::make('password')->required()->min(8)->max()
        )->getRules();
    }

    public function authorize()
    {
        return true;
    }
}

```

### Example Controller

```php
<?php

namespace App\Http\Controllers\Auth;

use Alpayklncrsln\RuleSchema\RuleSchema;use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(\App\Http\Requests\Auth\LoginRequest $request)
    {
     $request->Validate(RuleSchema::create(
         Rule::make('email')->required()->email()->max()->min()->exists('users', 'email'),
         Rule::make('password')->required()->min(8)->max(),
     )->getRules());
    
     .
     . 
       
    }
}

```

### Create Rule Schema

```php
$ruleSchema = Alpayklncrsln\RuleSchema\RuleSchema::create(
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
)->getRules();
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
)->getRules();
```

Or

```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$ruleSchema = DefaultRuleSchema::register()->getRules();
```

#### Reset Password

```php    
\Alpayklncrsln\RuleSchema\RuleSchema::create(
      Alpayklncrsln\RuleSchema\Rule::make('email')->required()->email()->max()->exists('users', 'email'),
      Alpayklncrsln\RuleSchema\Rule::make('token')->required()->exists('password_resets', 'token'),
      Alpayklncrsln\RuleSchema\Rule::make('password')->required()->min(8)->max()->confirmed(),
)->getRules();
```

Or

```php    
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$ruleSchema = DefaultRuleSchema::resetPassword()->getRules();
```


## Available Rules

Laravel Validation Docs: [Rules](https://laravel.com/docs/11.x/validation#available-validation-rules) all rules are available.

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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
