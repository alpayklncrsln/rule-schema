# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema, Laravel uygulamalarında doğrulama kuralları oluşturmak için akıcı bir arayüz sağlayan güçlü bir Laravel paketidir. Karmaşık doğrulama kurallarını temiz ve okunabilir kod yapısını koruyarak oluşturma sürecini basitleştirir. Paket, yaygın kimlik doğrulama senaryoları için önceden hazırlanmış doğrulama şemaları sunar ve tüm Laravel doğrulama kurallarını destekler.

## Özellikler

- Doğrulama kuralları için akıcı arayüz
- Kimlik doğrulama senaryoları için hazır şemalar (giriş, kayıt, şifre sıfırlama)
- Tüm Laravel doğrulama kuralları için destek
- Koşullu kural uygulama
- Kural birleştirme ve düzenleme
- Tip güvenli kural oluşturma
- Form Request'ler ile kolay entegrasyon
- Temiz ve sürdürülebilir doğrulama mantığı
- Pakete özel Form request sınıfı oluşturma

## Kurulum

Paketi composer ile kurabilirsiniz:

```bash
composer require alpayklncrsln/rule-schema
```

Paketin Form Requestini Eklemek İçin:
````aiignore
php artisan make:rule-schema Product
````

Rule Schema Form Request'inizi oluşturun:

````php
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
````


## Temel Kullanım

İşte doğrulama kuralları oluşturmanın basit bir örneği:
##### RuleSchema  Metodları

- `create` - Kuralları oluşturmak için kullanılır.
- `when` - Kurralları koşulu sağladığı takdirde şemaya eklemek için kullanılır.
- `expect` - Gerekli kuralları silmek için kullanılır.
- `merge` - Kuralları birleştirme için kullanılır.
- `existsMerge` - Önceden oluşturulan şemada alan  varsa, kural eklemek için kullanılır.
- `auth` - Oturum aktifse kuralları eklemek için kullanılır.
- `notauth` - Oturum aktif değilse kuralları eklemek için kullanılır.
- `putSchema` - İstek metodu PUT ise kuralları eklemek için kullanılır.
- `postSchema` - İstek metodu POST ise kuralları eklemek için kullanılır.
- `patchSchema` - İstek metodu PATCH ise kuralları eklemek için kullanılır.
- `arraySchema` - İç içe şema eklemek için kullanılır.
- `getRules` - Oluşturulan kuralları almak için kullanılır.

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)->max(255)
)->getRules();
```

Çıktı:

```php
[
    'email' => ['required', 'email', 'max:255'],
    'password' => ['required', 'min:8', 'max:255'],
]
```

## Form Request ile Entegrasyon

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
Çıktı:

```php
[
    'email' => ['required', 'email', 'exists:users,email'],
    'password' => ['required', 'min:8']
]
```

## Hazır Kimlik Doğrulama Şemaları

Paket, yaygın kimlik doğrulama senaryoları için hazır şemalar içerir:

### Giriş (Login)
```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

// Hazır şema kullanımı
$rules = DefaultRuleSchema::login()->getRules();

// Veya özel uygulama
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->exists('users', 'email'),
    Rule::make('password')->required()->min(8)
)->getRules();
```

### Kayıt (Register)
```php
// Hazır şema kullanımı
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
$rules = DefaultRuleSchema::register()->getRules();

// Veya özel uygulama
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Rule;
$rules = RuleSchema::create(
    Rule::make('name')->required()->max(255),
    Rule::make('email')->required()->email()->unique('users'),
    Rule::make('password')->required()->min(8)->confirmed()
)->getRules();
```


## Gelişmiş Özellikler

### Koşullu Kurallar
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
Çıktı:
```php
[
    'role' => ['required', 'in:admin,user'],
    'permissions' => ['required', 'array']
]

```

### Kural Birleştirme
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
Çıktı:
```php
[
    'name' => ['required'],
    'email' => ['required', 'email']
]
```

### Kuralları Hariç Tutma
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

Çıktı:

```php
[
    'name' => ['required'],
    'email' => ['required', 'email']
]
```

### RuleSchema ile ``arraySchema`` Kullanımı
- `arraySchema` metodu ile iç içe  kurallarını belirleyebilirsiniz.
- `isMultiple` değişkeni ile çoklu olup olmadığını belirliyebilirsiniz.
- `methods` değişkeni istek metodlarını belirliyebilirsiniz.Hangi durmda eklenceğini belirler. 
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

Çıktı:

```php   
[
    'name' => ['required'],
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],  
    'roles.*.name' => ['required', 'string', 'max:255'], 
]
```

### MultiStep Kurallar
Step by step kural uygulama için `MultiStepSchema` sınıfını kullanabilirsiniz.
- `step` metodu ile her bir step'e kural uygulayabilirsiniz.
- `lastStep` metodu ile son step'e kural uygulayabilirsiniz.
- `allSteps` metodu ile tüm stepleri tek seferde uygulayabilirsiniz.
- `setAllSteps` metodu ile tüm stepleri tek seferde uygulama durumunu ayarlayabilirsiniz.
- `getRules` metodu ile kurallarını alabilirsiniz.

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
Çıktı:

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
### MultiStep `AllSteps` Kullanımı
AllSteps ile tüm stepleri tek seferde uygulayabilirsiniz.
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
çıktı:
```php
[
    'step_1.name' => ['required'],
    'step_1.email' => ['required', 'email'],
    'step_2.password' => ['required']
]
```

## Dosya İşlemleri İlgili Kurallar
`mimeAndMimetypes` metodu ile dosya türlerini belirleyebilirsiniz.
Tek fonksiyonla hem mimes hemde mimetypes de belirleyebilirsiniz.
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
çıktı:

```php
[
    'image' => ['required', 'image',
     'mimes:jpeg,jpg,png', 
     'mimetypes:image/jpeg,image/jpg,image/png'
     ]
]
```

## Mevcut Metodlar

Paket, aşağıdakiler de dahil olmak üzere tüm Laravel doğrulama kurallarını destekler:

- Temel doğrulama (required, string, numeric, vb.)
- Tarih doğrulama (date, after, before, vb.)
- Dosya doğrulama (file, image, mimes, vb.)
- Boyut doğrulama (min, max, between, vb.)
- Veritabanı doğrulama (unique, exists)
- Özel doğrulama kuralları

Mevcut tüm kuralların listesi için [Laravel Doğrulama Dokümantasyonu](https://laravel.com/docs/11.x/validation#available-validation-rules)'na bakınız.

## Test

```bash
composer test
```

## Katkıda Bulunma

Detaylar için lütfen [CONTRIBUTING](CONTRIBUTING.md) dosyasını inceleyin.

## Güvenlik Açıkları

Güvenlik açıklarını nasıl raporlayacağınızı öğrenmek için [güvenlik politikamızı](../../security/policy) inceleyin.

## Katkıda Bulunanlar

- [Alpay Kılınçarslan](https://github.com/alpayklncrsln)
- [Tüm Katkıda Bulunanlar](../../contributors)

## Lisans

MIT Lisansı (MIT). Daha fazla bilgi için [Lisans Dosyası](LICENSE.md)'nı inceleyiniz. 
