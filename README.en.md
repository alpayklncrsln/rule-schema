# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema is a **fluent, type-safe, context-aware, and highly performant** validation schema library designed for
Laravel applications.

Instead of writing traditional, error-prone, and hard-to-maintain pipe-delimited string rules (e.g.
`'email' => 'required|email|max:255|unique:users,email'`), it provides a clean, object-oriented, and developer-friendly
code layout with full IDE autocomplete support strictly following SOLID principles.

---

## 🚀 Key Features

- **Type-Safe Chaining (Fluent Builders)**: Dedicated builder classes (`StringRuleBuilder`, `NumericRuleBuilder`, etc.)
  make sure your IDE only suggests validation rules appropriate to the chosen data type.
- **Nested Array & JSON Validation (`children` & `each`)**: Validate complex nested JSON structures, multi-level
  objects, or dynamic lists without manual dot-notation configuration (e.g. no need to manually manage
  `'user.profile.age'`).
- **Direct Value Validation**: Validate a single value, variable, or nested array directly through a rule builder object
  using `validate($value)`, `passes($value)`, and `fails($value)` helper methods. No array wrapping or Form Request
  class required.
- **PHP Backed Enum Support**: Validate standard PHP Backed Enum classes directly with `inEnum()` and `notInEnum()`
  methods without the overhead of creating a manual rule object instance (`new Enum(...)`).
- **Dynamic Extensions (Macroable)**: Add custom validation rules dynamically to builder classes at runtime using
  standard Laravel macros without having to fork the library.
- **Multiple Images and Files Upload Validation (`images` & `files`)**: Easily validate multiple file uploads and array
  inputs (`gallery.*`, `attachments.*`) in a single line: `R::images('gallery')->max(5)`.
- **File Type & Security Shortcuts**: Built-in shortcut filters such as `audioOnly()`, `imageOnly()`, `documentOnly()`,
  `archiveOnly()`, `noHtml()`, and `passwordSecurity()` are immediately ready to use.
- **Fluent Database Modifiers**: Chain additional query filters (`where`, `whereNot`, `whereNull`, `onlyTrashed`, etc.)
  directly after adding `unique()` or `exists()` database rules in the builder chain.
- **Conditional Rules (`when` & `unless`)**: Apply validation rules dynamically depending on runtime boolean evaluations
  directly within the builder chain.
- **Livewire & Filament Integration**: Easily bind schema validations and custom error messages to component validation
  lifecycles using the custom `InteractsWithRuleSchema` trait.
- **Ready-Made Shortcuts & Schemas**: Commonly repeated schemas (`login`, `register`, `contact`) and specific validation
  shortcuts (`email`, `phoneNumber`, `password`, `uuid`, etc.) are pre-configured and ready to use in a single line.
- **Compilation Cache (Memoization) - $O(1)$ Performance**: To prevent redundant CPU cycles and memory allocations when
  parsing deep/complex nested schemas, a compiler memoization caching layer is built into the builders.
- **Auto-Generation from Database**: Automatically inspects database columns and Eloquent models to generate
  corresponding nullable, size, and type validation rules.
- **100% Backwards Compatible**: Existing legacy `Rule::make(...)` code runs smoothly without changing a single line.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require alpayklncrsln/rule-schema
```

---

## 🛠️ Quick Start

### 1. Classical Usage (100% Backwards Compatible)

All validation rules defined with the legacy syntax are preserved and compile exactly as before:

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)
)->getRules();

// Compiled Laravel Rules:
// [
//     'email' => ['required', 'email', 'max:255'],
//     'password' => ['required', 'min:8']
// ]
```

### 2. New Type-Safe Usage (`R` Facade)

The `R` class is the main entry point to eliminate spelling mistakes and unlock full IDE autocompletion:

```php
use R;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    R::string('username')->required()->min(3)->max(30),
    R::numeric('age')->required()->integer()->min(18),
    R::date('birthday')->required()->dateFormat('Y-m-d')
)->getRules();

// Compiled Laravel Rules:
// [
//     'username' => ['string', 'required', 'min:3', 'max:30'],
//     'age' => ['numeric', 'required', 'integer', 'min:18'],
//     'birthday' => ['date', 'required', 'date_format:Y-m-d']
// ]
```

---

## 💡 Detailed Features and Examples

### 1. Type-Specific Builder Classes

Initializing a specific type builder focuses your IDE autocomplete on methods matching that type:

#### **String (Text)** -> `R::string()`
```php
$rules = RuleSchema::create(
    R::string('bio')
        ->required()
        ->alphaNumeric()
        ->min(10)
        ->max(500)
        ->uppercase()
        ->timezone()
)->getRules();
```

#### **Numeric (Numbers)** -> `R::numeric()`
```php
$rules = RuleSchema::create(
    R::numeric('price')
        ->required()
        ->decimal(2, 4) // Decimal places constraint
        ->min(10)
        ->max(1000)
        ->multipleOf(5) // Must be a multiple of 5
)->getRules();
```

#### **Date (DateTime)** -> `R::date()`
```php
$rules = RuleSchema::create(
    R::date('published_at')
        ->required()
        ->dateFormat('Y-m-d H:i:s')
        ->after('today') // Must be after today
        ->before('tomorrow')
)->getRules();
```

#### **File (Files & Images)** -> `R::file()`

```php
$rules = RuleSchema::create(
    R::file('avatar')
        ->required()
        ->image() // Image validation
        ->mimes('png', 'jpg', 'webp')
        ->max(2048) // Max 2 MB (kilobytes)
        ->min(100) // Min 100 KB
        ->dimensionsImageWidthHeight(800, 600) // Precise dimensions check
)->getRules();
```

#### **Array (Arrays)** -> `R::array()`

```php
$rules = RuleSchema::create(
    R::array('categories')
        ->required()
        ->min(1)
        ->max(5)
)->getRules();
```

---

### 2. Nested Array and List Validations (`children` & `each`)

Eliminates dot-notation configuration boilerplate when validating nested JSON structures and API request payloads.

#### **A. Object / Associative Structure** -> `children()`

Validates named child attributes inside a parent associative array:

```php
$rules = RuleSchema::create(
    R::array('profile')->required()->children([
        R::string('first_name')->required()->max(50),
        R::string('last_name')->required()->max(50),
        R::string('email')->required()->email()
    ])
)->getRules();

// Compiled Laravel Rules:
// [
//     'profile' => ['array', 'required'],
//     'profile.first_name' => ['string', 'required', 'max:50'],
//     'profile.last_name' => ['string', 'required', 'max:50'],
//     'profile.email' => ['string', 'required', 'email']
// ]
```

#### **B. List / Indexed Structure** -> `each()`

Validates that every single item in a list conforms to the same kural set:

```php
$rules = RuleSchema::create(
    R::array('tags')->required()->each(
        R::string()->min(3)->max(20)
    )
)->getRules();

// Compiled Laravel Rules:
// [
//     'tags' => ['array', 'required'],
//     'tags.*' => ['string', 'min:3', 'max:20']
// ]
```

#### **C. Deeply Nested Arrays**

Nest `children()` and `each()` calls inside one another recursively to represent complex objects and trees safely:

```php
$rules = RuleSchema::create(
    R::array('order')->children([
        R::string('coupon_code')->nullable(),
        R::array('items')->required()->each(
            R::array()->children([
                R::string('product_id')->required()->uuid(),
                R::numeric('quantity')->required()->integer()->min(1),
                R::numeric('price')->required()->decimal(2)
            ])
        )
    ])
)->getRules();

// Compiled Laravel Rules:
// [
//     'order' => ['array'],
//     'order.coupon_code' => ['string', 'nullable'],
//     'order.items' => ['array', 'required'],
//     'order.items.*' => ['array'],
//     'order.items.*.product_id' => ['string', 'required', 'uuid'],
//     'order.items.*.quantity' => ['numeric', 'required', 'integer', 'min:1'],
//     'order.items.*.price' => ['numeric', 'required', 'decimal:2']
// ]
```

---

### 3. Multiple Image and File Validation (`R::images` & `R::files`)

To simplify validating array file uploads, specialized shortcuts are available. They return an `ArrayRuleBuilder`
enabling fluent constraints (`min`, `max`) on the array itself. An optional callback allows customizing the nested file
properties:

```php
$rules = RuleSchema::create(
    // Validate minimum 1 and maximum 5 images uploaded:
    R::images('gallery')->min(1)->max(5),

    // Advanced nested file customization (max 4MB, only png or jpg):
    R::images('photos', function ($file) {
        $file->max(4096)->mimes(null, 'png', 'jpg');
    }),

    // Multiple raw document attachments (max 3 files):
    R::files('attachments')->max(3)
)->getRules();

// Compiled Laravel Rules:
// [
//     'gallery' => ['array', 'min:1', 'max:5'],
//     'gallery.*' => ['file', 'image'],
//     'photos' => ['array'],
//     'photos.*' => ['file', 'image', 'max:4096', 'mimes:png,jpg'],
//     'attachments' => ['array', 'max:3'],
//     'attachments.*' => ['file']
// ]
```

---

### 4. PHP Backed Enum Validation Helpers (`inEnum` & `notInEnum`)

Validate standard PHP Backed Enum classes directly in the builder chain using `inEnum()` and `notInEnum()` methods:

```php
use App\Enums\UserRole;

$rules = RuleSchema::create(
    R::string('role')->required()->inEnum(UserRole::class),
    R::string('invalid_role')->notInEnum(UserRole::class)
)->getRules();

// Compiled Laravel Rules:
// [
//     'role' => ['string', 'required', new \Illuminate\Validation\Rules\Enum(UserRole::class)],
//     'invalid_role' => ['string', 'not_in:admin,user,editor'] // Enum case values resolved dynamically
// ]
```

---

### 5. Dynamic Extension via Macros (Macroable)

All Builder classes inherit Laravel's `Macroable` trait. This allows you to dynamically register custom validation
chains at runtime:

```php
use Alpayklncrsln\RuleSchema\BaseRuleBuilder;

// Inside your AppServiceProvider boot() method:
BaseRuleBuilder::macro('tcKimlikNo', function (?string $message = null) {
    return $this->regex('/^[1-9][0-9]{9}[02468]$/', $message);
});

// Easily call it across all validation schemas:
$rules = RuleSchema::create(
    R::string('identity_number')->required()->tcKimlikNo('Invalid ID number.')
)->getRules();
```

---

### 6. File Type Groups & Security Shortcuts

#### **A. MIME Groups Shortcuts**

Instead of manually declaring file extensions, utilize built-in groupings:

- `audioOnly()`: Validates common audio formats (resolves from `AudioMime` enum cases).
- `imageOnly()`: Validates image formats (resolves from `ImageMime` enum cases).
- `documentOnly()`: Validates document extensions (resolves from `FileMime` enum cases: `PDF`, `DOC`, `DOCX`, `XLS`,
  `XLSX`, `PPT`, `PPTX`, `TXT`, `RTF`).
- `archiveOnly()`: Validates archive extensions (resolves from `FileMime` enum cases: `ZIP`, `TAR`, `GZ`, `RAR`, `7Z`).

```php
$rules = RuleSchema::create(
    R::file('music_file')->audioOnly(),
    R::file('user_photo')->imageOnly(),
    R::file('cv_document')->documentOnly(),
    R::file('backup_file')->archiveOnly()
)->getRules();
```

#### **B. Security and Format Helpers**

- `alphaDashOrSpace()`: Allows letters, numbers, dashes, underscores, and spaces. Perfect for display names and titles.
- `noHtml()`: Protects against XSS injection by ensuring the string doesn't contain HTML or XML tags.
- `passwordSecurity()`: Configures Laravel's native, highly secure `Password` validation rule object (`letters`,
  `mixedCase`, `symbols`, `uncompromised`) fluently.

```php
$rules = RuleSchema::create(
    R::string('display_name')->required()->alphaDashOrSpace(),
    R::string('user_comment')->required()->noHtml('HTML tags are not allowed.'),
    R::string('password')->required()->passwordSecurity(
        min: 10,
        mixedCase: true,
        symbols: true,
        uncompromised: true // Ensures password has not been leaked in data breaches
    )
)->getRules();
```

---

### 7. Fluent Database Modifiers

Directly query validation filters right after configuring `unique()` or `exists()` rules within the builder chain:

```php
$rules = RuleSchema::create(
    R::string('email')
        ->required()
        ->unique('users') // Unique constraint on users table
        ->where('status', 'active') // status = 'active'
        ->whereNot('role', 'admin') // role != 'admin'
        ->whereNull('banned_at') // banned_at IS NULL
        ->withoutTrashed() // SoftDeletes filter
)->getRules();
```

**Supported Database Modifiers:**

- `where($column, $value)`: Add an equality constraint (supports Closure or string).
- `whereNot($column, $value)`: Add an inequality constraint.
- `whereNull($column)`: Add a `NULL` constraint.
- `whereNotNull($column)`: Add a `NOT NULL` constraint.
- `onlyTrashed()`: Search soft-deleted records only.
- `withoutTrashed()`: Exclude soft-deleted records from search.

---

### 8. Fluent Conditional Rules (`when` & `unless`)

Chain validation rule modifiers conditionally at runtime:

```php
$isPremium = true;

$rules = RuleSchema::create(
    R::string('bio')
        ->nullable()
        ->when($isPremium, fn($rule) => $rule->max(1000)) // Max 1000 characters if premium
        ->unless($isPremium, fn($rule) => $rule->max(100)) // Max 100 characters if not premium
)->getRules();
```

---

### 9. Custom Laravel Rules (`custom`)

Integrate invokable rules, custom Laravel validation classes, or inline closures:

```php
use App\Rules\ValidTcNo;

$rules = RuleSchema::create(
    R::string('identity_number')
        ->required()
        ->custom(
            new ValidTcNo(),
            fn($attribute, $value, $fail) => str_starts_with($value, '0') ? $fail('Cannot start with 0.') : null
        )
)->getRules();
```

---

### 10. Livewire and Filament Integration

Add the `InteractsWithRuleSchema` trait to your Livewire or Filament component class, and define a `ruleSchema()`
method. Rules and error message mappings are automatically handled:

```php
use Alpayklncrsln\RuleSchema\Traits\InteractsWithRuleSchema;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\R;
use Livewire\Component;

class ProfileSettings extends Component
{
    use InteractsWithRuleSchema;

    public string $name = '';
    public string $email = '';
    public string $phone = '';

    // Define your rule schema here:
    public function ruleSchema(): RuleSchema
    {
        return RuleSchema::create(
            R::string('name')->required()->max(50),
            R::email(), // Prepackaged shortcut
            R::phoneNumber('phone') // Prepackaged shortcut
        );
    }

    public function submit()
    {
        // Validates all component properties against your defined rule schema
        $this->validate(); 
        
        // Save operations...
    }
}
```

---

### 11. Prepackaged Reusable Schemas and Rules

Pre-defined validation templates save time and keep request rules DRY.

#### **A. Reusable Schemas (Aggregates)**

Loads validation rules for multiple fields commonly used together:

- `R::login(bool $remember = true)`: Email, password, and optional remember-me rules.
- `R::register(bool $passwordConfirmation = true)`: Name, email, password, and confirmed password rules.
- `R::resetPassword()`: Token, email, and password confirmation.
- `R::updatePassword()`: Password rules for changing passwords.
- `R::contact()`: Name, email, message, and accepted checkbox rules.
- `R::feedback()`: Name, email, message, and rating (1-5) rules.

*Example: Extending the login schema:*

```php
$rules = RuleSchema::create(
    RuleSchema::login(), // Loads default email & password validations
    R::string('captcha_token')->required() // Chain custom additions
)->getRules();
```

#### **B. Reusable Field Rules (Shortcuts)**

Shortcut methods for fast configuration:

- `R::name($attribute = 'name', ...)`: Standard user name validation rules.
- `R::email($attribute = 'email')`: Email format validation.
- `R::password($attribute = 'password')`: Standard password validation (min 8 chars).
- `R::phoneNumber($attribute = 'phone_number')`: Regular expression phone number check.
- `R::postalCode($attribute = 'postal_code')`: Postcode validation format.
- `R::uuid($attribute = 'uuid')`: UUID format validation.
- `R::ulid($attribute = 'ulid')`: ULid format validation.
- `R::url($attribute = 'url')`: URL validation (http/https).
- `R::image($attribute = 'image')`: Image format and size rules.
- `R::images($attribute = 'images', ?callable $callback = null)`: Multiple images array validation.
- `R::files($attribute = 'files', ?callable $callback = null)`: Multiple files array validation.
- `R::text($attribute = 'text')`: Large text blocks (up to 16K characters).
- `R::longText($attribute = 'long_text')`: Deep text blocks (up to 65K characters).

---

### 12. Direct Value & Variable Validation (Direct Value Validation)

To validate a single value, variable, or nested array directly without wrapping it in a complete schema, you can call
`validate($value)`, `passes($value)`, and `fails($value)` helper methods directly on any builder object. This simplifies
simple validation checks and keeps code clean:

```php
$email = 'not-an-email';

// 1. Boolean check
if (R::string()->email()->fails($email)) {
    // Handle invalid email state...
}

if (R::string()->email()->passes('test@example.com')) {
    // Handle valid email state...
}

// 2. Validate and retrieve value (throws ValidationException if invalid)
try {
    $validatedEmail = R::string()->email()->validate('user@domain.com');
} catch (\Illuminate\Validation\ValidationException $e) {
    $errors = $e->errors();
}

// 3. Directly validate nested arrays
$profileData = [
    'name' => 'Alpay',
    'email' => 'alpay@example.com'
];

$profileRule = R::array()->children([
    R::string('name')->required(),
    R::string('email')->required()->email()
]);

if ($profileRule->passes($profileData)) {
    // Profile array is valid...
}
```

---

### 13. Inline Array Validation (`validate`)

Use schemas to validate raw arrays outside HTTP requests, such as in job queues, console commands, or event listeners:

```php
$data = [
    'name' => 'Alpay',
    'age' => 25
];

try {
    $validated = RuleSchema::create(
        R::string('name')->required(),
        R::numeric('age')->required()->integer()->min(18)
    )->validate($data);
    
    // Returns validated data array on success
} catch (\Illuminate\Validation\ValidationException $e) {
    // Catch validation failures
    $errors = $e->errors();
}
```

---

### 14. Multi-Step Form Wizard (`MultiStepSchema`)

Easily split validation across multiple steps:

```php
use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use Alpayklncrsln\RuleSchema\R;

// Resolves rules according to the HTTP request 'step' parameter:
$rules = MultiStepSchema::make('step')
    ->step(1,
        R::string('name')->required(),
        R::email()
    )
    ->step(2,
        R::password()
    )
    ->getRules();
```

---

### 15. Database Schema Auto-Generation (`RuleSchema::model`)

Inspects database schemas dynamically to generate rules from database column types and nullability constraints:

```php
use App\Models\Product;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::model(Product::class)->getRules();
```

---

### 16. Compilation Cache (Memoization) - Performance & Memory Optimizations

Deeply nested schemas with multiple `children()` and `each()` chains can become expensive to recursively compile on
every request.

Rule Schema incorporates an internal **memoization (compilation cache)** within the base `BaseRuleBuilder`. Rules and
validation structures are compiled once on their first access and cached in memory. Subsequent calls to `getRules()`
retrieve compiled data instantly in $O(1)$ complexity, allocating zero new memory objects. If any rule or message on the
chain is modified, the cache is automatically invalidated and re-compiled on the next call.

---

### 17. Data Sanitization & Casting (Transformers)

Rule Schema goes beyond validation by allowing you to clean, sanitize, cast, and define fallback/default values for
request parameters inline. Once validation passes, the parameters are automatically mutated and returned in their
correct formats.

#### **Core Sanitizers (Available on all Builders)**

- `default(mixed $value)`: Falls back to the given default value if the input is `null`.
- `sanitize(callable $callback)`: Runs a custom callback to clean or mutate the parameter.
- `transform(callable $callback)`: Alias of the `sanitize()` method.

#### **String-Specific Sanitizers (`R::string()`)**

- `trim()`: Trims whitespace from both ends of the string.
- `lower()`: Converts the string to lowercase (multi-byte safe).
- `upper()`: Converts the string to uppercase (multi-byte safe).
- `stripTags()`: Strips HTML and PHP tags (ideal for basic XSS prevention).
- `slug()`: Converts the string to a URL-friendly slug.

#### **Numeric-Specific Casting (`R::numeric()`)**

- `castToInt()`: Casts the numeric value to PHP `int`.
- `castToFloat()`: Casts the numeric value to PHP `float`.

#### **Date-Specific Casting (`R::date()`)**

- `castToCarbon()`: Converts the date string/value directly into a `Carbon\Carbon` instance.

#### **Example Usage:**

```php
$validatedData = RuleSchema::create(
    R::string('name')->required()->trim()->lower(),
    R::numeric('age')->nullable()->default(18)->castToInt(),
    R::date('published_at')->castToCarbon(),
    R::array('tags')->each(R::string()->trim()->lower())
)->validate($request->all());

// $validatedData['name'] -> converts '  JOHN  ' to 'john'
// $validatedData['age'] -> converts null to 18 (int)
// $validatedData['published_at'] -> converts '2026-06-21' to Carbon instance
// $validatedData['tags'] -> converts ['  PHP ', ' Laravel '] to ['php', 'laravel']
```

---

## 💻 Artisan CLI Commands

Accelerate your workflow with the following code generation tools:

### A. Create Validation Request Classes (`make:rule-schema`)

Generate request validation classes integrated with Rule Schema:

```bash
php artisan make:rule-schema ProductRequest
```

Include the `--m` option to prefill request validation rules automatically based on database columns of the model:

```bash
php artisan make:rule-schema ProductRequest --m
```

### B. Create Auth Request Classes (`rule-schema:auth`)

Instantly generate request validators (LoginRequest, RegisterRequest, etc.) prepackaged with rule schemas:

```bash
# Generate all auth request classes:
php artisan rule-schema:auth all

# Generate specific auth classes:
php artisan rule-schema:auth login
php artisan rule-schema:auth register
```

---

## 🧪 Testing

The package achieves 100% test coverage. To execute the Pest PHP suite:

```bash
composer test
```

To run static analysis check via PHPStan:

```bash
composer analyse
```

---

## 📄 License

This package is open-source software licensed under the [MIT License](LICENSE.md).
