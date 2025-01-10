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

## Kurulum

Paketi composer ile kurabilirsiniz:

```bash
composer require alpayklncrsln/rule-schema
```

## Temel Kullanım

İşte doğrulama kuralları oluşturmanın basit bir örneği:

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)->max(255)
)->getRules();
```

## Form Request ile Entegrasyon

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

## Hazır Kimlik Doğrulama Şemaları

Paket, yaygın kimlik doğrulama senaryoları için hazır şemalar içerir:

### Giriş (Login)
```php
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

// Hazır şema kullanımı
$rules = DefaultRuleSchema::login()->getRules();

// Veya özel uygulama
$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->exists('users', 'email'),
    Rule::make('password')->required()->min(8)
)->getRules();
```

### Kayıt (Register)
```php
// Hazır şema kullanımı
$rules = DefaultRuleSchema::register()->getRules();

// Veya özel uygulama
$rules = RuleSchema::create(
    Rule::make('name')->required()->max(255),
    Rule::make('email')->required()->email()->unique('users'),
    Rule::make('password')->required()->min(8)->confirmed()
)->getRules();
```

## Gelişmiş Özellikler

### Koşullu Kurallar
```php
$rules = RuleSchema::create()
    ->when(Auth::check(), 
        Rule::make('role')->required()->in(['admin', 'user']),
        Rule::make('permissions')->required()->array()
    )
    ->getRules();
```

### Kural Birleştirme
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

### Kuralları Hariç Tutma
```php
$rules = RuleSchema::create(
    Rule::make('name')->required(),
    Rule::make('email')->required()->email(),
    Rule::make('password')->required()
)
->expect('password')
->getRules();
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
