---
name: laravel-rule-schema
description: Model Context Protocol (MCP) and custom agent skill introducing the fluent, type-safe Rule Schema validation framework for Laravel applications. Use this to construct rules, schemas, and perform direct validation.
---

# Laravel Rule Schema (R Facade & Builders)

This guide introduces the `Rule Schema` validation architecture for Laravel. Whenever writing or refactoring validation
rules, Form Requests, controllers, or Livewire components in this codebase, **always** prefer using this type-safe,
fluent builder API rather than raw pipe-separated validation strings.

---

## 🚀 Key entrypoint: `R` Facade

Use `Alpayklncrsln\RuleSchema\R` to instantiate type-specific builders:

- **String (Text)**: `R::string($attr = '')` -> returns `StringRuleBuilder`
- **Numeric (Numbers)**: `R::numeric($attr = '')` -> returns `NumericRuleBuilder`
- **Date (DateTime)**: `R::date($attr = '')` -> returns `DateRuleBuilder`
- **File (Uploads)**: `R::file($attr = '')` -> returns `FileRuleBuilder`
- **Array (Iterables/Assoc)**: `R::array($attr = '')` -> returns `ArrayRuleBuilder`

---

## 🛠️ Validation Builders & Available Methods

### 1. Common Builders & Modifiers (Inherited by all type builders)

- `required(bool $check = true, ?string $message = null)`
- `nullable(bool $check = true, ?string $message = null)`
- `sometimes(bool $check = true, ?string $message = null)`
- `custom(...$rules)`: Accepts custom Laravel `Rule` classes or closures.
- `when(bool $condition, callable $callback)`: Applies rules conditionally.
- `unless(bool $condition, callable $callback)`: Applies rules unless a condition is met.
- **Database Modifiers** (Used after `.unique('table')` or `.exists('table')`):
    - `where(string|Closure $column, $value = null)`
    - `whereNot(string $column, $value)`
    - `whereNull(string $column)`
    - `whereNotNull(string $column)`
    - `onlyTrashed()` / `withoutTrashed()`
- **Enum Support**:
    - `inEnum(string $enumClass, ?string $message = null)`
    - `notInEnum(string $enumClass, ?string $message = null)`

### 2. String Validation Methods (`R::string()`)

- `min(int $min)` / `max(int $max)` / `between(int $min, int $max)`
- `email(bool $dnsCheck = false, ...)`
- `alphaNumeric()` / `alphaDashOrSpace()`
- `noHtml(?string $message = null)`: XSS validation check.
- `passwordSecurity(int $min = 8, bool $mixedCase = false, bool $symbols = false, ...)`
- `uuid()` / `ulid()` / `url()` / `timezone()`

### 3. Numeric Validation Methods (`R::numeric()`)

- `integer()` / `decimal(int $min, ?int $max = null)`
- `min(int $min)` / `max(int $max)` / `between(int $min, int $max)`
- `multipleOf(int|float $value)`

### 4. File Validation Methods (`R::file()`)

- `image()`
- `mimes(?string $message = null, string|MimeEnumInterface ...$mimes)`
- `max(int $maxKilobytes)` / `min(int $minKilobytes)` / `size(int $kilobytes)` / `between(int $min, int $max)`
- `audioOnly()` / `imageOnly()` / `documentOnly()` / `archiveOnly()`: MIME group shortcuts utilizing backed enums (
  `AudioMime`, `ImageMime`, `FileMime`).

### 5. Array & Nested Structure Validation (`R::array()`)

- `children(array $builders)`: Asserts validation for child properties (associative array).
- `each(BaseRuleBuilder $builder)`: Asserts validation for all items inside the list.

---

## 🧹 Data Sanitization & Casting (Transformers)

Rule Schema goes beyond validation: it allows inline parameter transformation, casting, and default value setting in the
same builder chain. When you validate a schema or a direct value, the output is formatted automatically.

### 1. Base Sanitizers (Available on all builders)

- `default(mixed $value)`: Falls back to a default value if the input is null.
- `sanitize(callable $callback)`: Applies a custom sanitization closure.
- `transform(callable $callback)`: Alias of `sanitize()`.

### 2. String-Specific Sanitizers (`R::string()`)

- `trim()`: Trims whitespace.
- `lower()`: Converts string to lowercase (mb-safe).
- `upper()`: Converts string to uppercase (mb-safe).
- `stripTags()`: Strips HTML and PHP tags.
- `slug()`: Converts string to a URL-friendly slug.

### 3. Numeric-Specific Casting (`R::numeric()`)

- `castToInt()`: Casts value to `int`.
- `castToFloat()`: Casts value to `float`.

### 4. Date-Specific Casting (`R::date()`)

- `castToCarbon()`: Casts date string/value to a `Carbon\Carbon` instance.

### Example usage:

```php
$validatedData = RuleSchema::create(
    R::string('name')->required()->trim()->lower(),
    R::numeric('age')->nullable()->default(18)->castToInt(),
    R::date('published_at')->castToCarbon()
)->validate($request->all());

// $validatedData['name'] is trimmed and lowercase.
// $validatedData['age'] is cast to integer (or defaults to 18).
// $validatedData['published_at'] is a Carbon instance.
```

---

## ⚡ Direct Value & Variable Validation

You can validate values directly without creating a `RuleSchema` wrapper or array:

- `passes($value): bool`: Check if a value is valid.
- `fails($value): bool`: Check if a value is invalid.
- `validate($value): mixed`: Get validated value or throw `ValidationException`.

```php
// Direct string validation
$isValid = R::string()->email()->passes($emailAddress);

// Direct nested array validation
$profileRule = R::array()->children([
    R::string('name')->required(),
    R::string('email')->required()->email()
]);
$profileRule->validate($profileData); // Throws ValidationException on error
```

---

## 📂 Aggregate Schemas and Shortcuts

- `R::login(bool $remember = true)`
- `R::register(bool $passwordConfirmation = true)`
- `R::resetPassword()`
- `R::updatePassword()`
- `R::contact()`
- `R::feedback()`
- `R::name($attribute = 'name', ...)`
- `R::email($attribute = 'email')`
- `R::password($attribute = 'password')`
- `R::phoneNumber($attribute = 'phone_number')`
- `R::images($attribute = 'images', ?callable $callback = null)`: Multiple images validation shortcut.
- `R::files($attribute = 'files', ?callable $callback = null)`: Multiple files validation shortcut.

---

## 🖥️ Controller / Livewire Example

```php
use Alpayklncrsln\RuleSchema\R;
use Alpayklncrsln\RuleSchema\RuleSchema;

// In a Standard Controller:
$rules = RuleSchema::create(
    R::string('username')->required()->min(3)->max(30),
    R::email(),
    R::images('gallery')->max(5)
)->getRules();

$request->validate($rules);
```
