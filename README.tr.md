# Rule Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/rule-schema/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alpayklncrsln/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alpayklncrsln/rule-schema/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/alpayklncrsln/rule-schema.svg?style=flat-square)](https://packagist.org/packages/alpayklncrsln/rule-schema)

Rule Schema, Laravel uygulamalarında doğrulama (validation) kuralları oluşturmak ve yönetmek için geliştirilmiş *
*akıcı (fluent), tip güvenli (type-safe), bağlama duyarlı (context-aware) ve yüksek performanslı** bir mimari
kütüphanedir.

Geleneksel, hata yapmaya açık ve yönetimi zor string tabanlı kurallar (örneğin:
`'email' => 'required|email|max:255|unique:users,email'`) yerine; IDE otomatik tamamlama (autocompletion) desteği sunan,
SOLID prensiplerine uygun, yüksek performanslı ve geliştirici dostu bir nesne yönelimli kod yazım deneyimi sağlar.

---

## 🚀 Öne Çıkan Özellikler

- **Tip Güvenli Zincirleme (Type-Safe Chaining)**: Metin, sayı, tarih, dosya ve dizi veri tiplerine özel olarak ayrılmış
  Builder sınıfları (`StringRuleBuilder`, `NumericRuleBuilder`, vb.) sayesinde IDE'niz sadece ilgili veri tipine uygun
  kuralları otomatik olarak önerir.
- **İç İçe Geçmiş Dizi ve JSON Doğrulaması (`children` & `each`)**: Karmaşık JSON yapılarını, çok seviyeli nesneleri
  veya dinamik listeleri nokta notasyonu (`user.profile.age`) kullanmadan, tamamen nesne odaklı ve özyinelemeli (
  recursive) olarak tanımlayabilirsiniz.
- **Doğrudan Tekil Değişken Doğrulaması**: Bir kural şemasını veya tekil kural builder nesnesini doğrudan değişkenleri
  doğrulamak için `validate($value)`, `passes($value)` ve `fails($value)` metotlarıyla çağırabilirsiniz. Dizi veya Form
  Request oluşturma zorunluluğu yoktur.
- **PHP Backed Enum Desteği**: Standart PHP Backed Enum sınıflarını `inEnum()` ve `notInEnum()` metotlarıyla, ek bir
  kural nesnesi (`new Enum(...)`) tanımlamaya gerek kalmadan doğrudan doğrulayabilirsiniz.
- **Dinamik Genişletilebilirlik (Macroable)**: Projenize özel doğrulama kurallarını makrolar ile builder sınıflarına
  dinamik olarak enjekte edebilir, kütüphaneyi çatallamadan genişletebilirsiniz.
- **Çoklu Dosya ve Resim Doğrulaması (`images` & `files`)**: Dizi halindeki çoklu dosya ve resim yüklemelerini (
  `gallery.*`, `attachments.*`) tek bir akıcı satırda (`R::images('gallery')->max(5)`) kolayca doğrulayabilirsiniz.
- **Dosya Tipi ve Güvenlik Kısayolları**: `audioOnly()`, `imageOnly()`, `documentOnly()`, `archiveOnly()` gibi dosya
  tipi grupları ile `noHtml()` ve `passwordSecurity()` gibi gelişmiş güvenlik kuralları kullanıma hazırdır.
- **Akıcı Veritabanı Modifikatörleri (Fluent Database Modifiers)**: `unique()` veya `exists()` kurallarından hemen sonra
  `where()`, `whereNot()`, `whereNull()`, `onlyTrashed()` gibi alt koşulları zincirleyebilirsiniz.
- **Koşullu Kurallar (`when` & `unless`)**: Mantıksal koşullara bağlı kural eklemelerini doğrudan builder zinciri
  içinden fluent olarak yapabilirsiniz.
- **Livewire & Filament Entegrasyonu**: Bileşenlerinize `InteractsWithRuleSchema` trait'ini dahil ederek, form
  doğrulamalarını ve hata mesajı haritalamalarını saniyeler içinde şemaya bağlayabilirsiniz.
- **Hazır Kısayol Şemalar ve Kural Şablonları**: Sıklıkla tekrarlanan `login`, `register`, `contact` gibi kural şemaları
  ile `email`, `phoneNumber`, `password`, `uuid` gibi alan kuralları önceden tanımlanmıştır. Tek satırda doğrudan
  kullanabilirsiniz.
- **Derleme Önbelleği (Compilation Memoization) - $O(1)$ Performans**: Özellikle karmaşık ve derin şemalarda kural
  derleme maliyetlerini ve CPU/Bellek tüketimini en aza indirmek için yerleşik bellek içi önbellekleme mekanizması
  entrege edilmiştir.
- **Modelden Otomatik Kural Üretimi**: Eloquent modelinizi veya veritabanı tablonuzu analiz ederek tüm doğrulama
  kurallarını ve tiplerini otomatik olarak türetir.
- **Geriye Dönük %100 Uyumluluk**: Mevcut projelerinizde kullanılan eski `Rule::make(...)` mimarisiyle tam uyumludur ve
  hiçbir kod değişikliği gerektirmeden çalışmaya devam eder.

---

## 📦 Kurulum

Paketi Composer aracılığıyla projenize dahil edebilirsiniz:

```bash
composer require alpayklncrsln/rule-schema
```

---

## 🛠️ Hızlı Başlangıç

### 1. Klasik Kullanım (Geriye Dönük %100 Uyumlu)

Eski sürüm projelerinizdeki kural tanımlamaları tamamen korunur ve sorunsuz çalışır:

```php
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    Rule::make('email')->required()->email()->max(255),
    Rule::make('password')->required()->min(8)
)->getRules();

// Derlenen Laravel Kuralları:
// [
//     'email' => ['required', 'email', 'max:255'],
//     'password' => ['required', 'min:8']
// ]
```

### 2. Yeni Tip Güvenli Kullanım (`R` Facade)

`R` sınıfı, yazım hatalarını sıfıra indiren ve IDE otomatik tamamlamasını en üst seviyeye çıkaran giriş noktasıdır:

```php
use Alpayklncrsln\RuleSchema\R;
use Alpayklncrsln\RuleSchema\RuleSchema;

$rules = RuleSchema::create(
    R::string('username')->required()->min(3)->max(30),
    R::numeric('age')->required()->integer()->min(18),
    R::date('birthday')->required()->dateFormat('Y-m-d')
)->getRules();

// Derlenen Laravel Kuralları:
// [
//     'username' => ['string', 'required', 'min:3', 'max:30'],
//     'age' => ['numeric', 'required', 'integer', 'min:18'],
//     'birthday' => ['date', 'required', 'date_format:Y-m-d']
// ]
```

---

## 💡 Detaylı Özellikler ve Örnekler

### 1. Tip-Özgü Builder Sınıfları

Hangi kural builder'ı ile başlarsanız, IDE sadece o veri tipine uygun kuralları otomatik olarak listeler:

#### **String (Metin)** -> `R::string()`
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

#### **Numeric (Sayısal)** -> `R::numeric()`
```php
$rules = RuleSchema::create(
    R::numeric('price')
        ->required()
        ->decimal(2, 4) // Ondalık basamak sınırlaması
        ->min(10)
        ->max(1000)
        ->multipleOf(5) // 5'in katı olmalı
)->getRules();
```

#### **Date (Tarih)** -> `R::date()`

```php
$rules = RuleSchema::create(
    R::date('published_at')
        ->required()
        ->dateFormat('Y-m-d H:i:s')
        ->after('today') // Bugünden sonra olmalı
        ->before('tomorrow')
)->getRules();
```

#### **File (Dosya ve Resim)** -> `R::file()`

```php
$rules = RuleSchema::create(
    R::file('avatar')
        ->required()
        ->image() // Resim dosyası doğrulaması
        ->mimes('png', 'jpg', 'webp')
        ->max(2048) // Maksimum 2 MB
        ->min(100) // Minimum 100 KB
        ->dimensionsImageWidthHeight(800, 600) // Tam genişlik/yükseklik filtreleme
)->getRules();
```

#### **Array (Dizi)** -> `R::array()`

```php
$rules = RuleSchema::create(
    R::array('categories')
        ->required()
        ->min(1)
        ->max(5)
)->getRules();
```

---

### 2. Dizi İçi Yapıların Doğrulaması (`children` & `each`)

JSON verilerini ve ilişkili listeleri doğrulamak için nokta notasyonuyla kural yazma zahmetini ortadan kaldırır.

#### **A. Obje / İlişkili Dizi Yapıları (Assoc Array)** -> `children()`

Bir dizi veya objenin içerdiği alt özellikleri doğrudan doğrular:

```php
$rules = RuleSchema::create(
    R::array('profile')->required()->children([
        R::string('first_name')->required()->max(50),
        R::string('last_name')->required()->max(50),
        R::string('email')->required()->email()
    ])
)->getRules();

// Derlenen Laravel Kuralları:
// [
//     'profile' => ['array', 'required'],
//     'profile.first_name' => ['string', 'required', 'max:50'],
//     'profile.last_name' => ['string', 'required', 'max:50'],
//     'profile.email' => ['string', 'required', 'email']
// ]
```

#### **B. Liste Yapıları (Indexed List / Collections)** -> `each()`

Bir dizi içindeki tüm elemanların aynı kural setine tabi olmasını sağlar:

```php
$rules = RuleSchema::create(
    R::array('tags')->required()->each(
        R::string()->min(3)->max(20)
    )
)->getRules();

// Derlenen Laravel Kuralları:
// [
//     'tags' => ['array', 'required'],
//     'tags.*' => ['string', 'min:3', 'max:20']
// ]
```

#### **C. Çok Seviyeli İç İçe Geçmiş Yapılar (Deeply Nested Payloads)**

`children()` ve `each()` metotlarını iç içe kullanarak son derece karmaşık veri ağaçlarını sıfır hata ile
tanımlayabilirsiniz:

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

// Derlenen Laravel Kuralları:
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

### 3. Çoklu Dosya ve Resim Doğrulaması (`R::images` & `R::files`)

Geliştiricilerin işini kolaylaştırmak için dizi tipindeki çoklu dosya yüklemelerini tek satırda doğrulayan kısayol
metotları eklenmiştir. Bu metotlar birer `ArrayRuleBuilder` döner ve dizi üzerindeki sınırlamaları (`min`, `max`)
zincirlemenizi sağlar. İsteğe bağlı callback parametresi ile içteki dosyaları yapılandırabilirsiniz:

```php
$rules = RuleSchema::create(
    // En az 1, en fazla 5 görsel yüklemesi:
    R::images('gallery')->min(1)->max(5),

    // Görsellerin detaylı kontrolü (maks 4MB ve sadece png, jpg):
    R::images('photos', function ($file) {
        $file->max(4096)->mimes(null, 'png', 'jpg');
    }),

    // Çoklu diğer belgeler:
    R::files('attachments')->max(3)
)->getRules();

// Derlenen Laravel Kuralları:
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

### 4. PHP Backed Enum Doğrulama Yardımcıları (`inEnum` & `notInEnum`)

Standart PHP Backed Enum sınıflarını doğrulamak için `inEnum()` ve `notInEnum()` metotları ile doğrulama işlemlerini çok
daha kısa hale getirebilirsiniz:

```php
use App\Enums\UserRole;

$rules = RuleSchema::create(
    R::string('role')->required()->inEnum(UserRole::class),
    R::string('invalid_role')->notInEnum(UserRole::class)
)->getRules();

// Derlenen Laravel Kuralları:
// [
//     'role' => ['string', 'required', new \Illuminate\Validation\Rules\Enum(UserRole::class)],
//     'invalid_role' => ['string', 'not_in:admin,user,editor'] // Enum case değerleri otomatik alınır
// ]
```

---

### 5. Makrolar ile Akıcı Sınıf Genişletme (Macroable)

Tüm Builder sınıfları Laravel'in `Macroable` trait'ine sahiptir. Bu sayede projenizde sürekli tekrar ettiğiniz özel
kural kalıplarını tek seferde tanımlayabilirsiniz:

```php
use Alpayklncrsln\RuleSchema\BaseRuleBuilder;

// AppServiceProvider boot() metodu içerisinde:
BaseRuleBuilder::macro('tcKimlikNo', function (?string $message = null) {
    return $this->regex('/^[1-9][0-9]{9}[02468]$/', $message);
});

// Artık tüm form kural tanımlamalarında doğrudan çağrılabilir:
$rules = RuleSchema::create(
    R::string('identity_number')->required()->tcKimlikNo('Geçersiz TC kimlik numarası.')
)->getRules();
```

---

### 6. Gelişmiş Dosya ve Güvenlik Kuralları (MIME Groups & Security)

#### **A. Dosya Tipi Grup Kısayolları (MIME Groups)**

Dosya uzantılarını tek tek yazmak yerine, ön tanımlı dosya gruplarını kullanabilirsiniz:

- `audioOnly()`: Ses dosyası formatlarını doğrular (MIME enumu üzerinden `AAC` vb.).
- `imageOnly()`: Resim dosyası formatlarını doğrular (`PNG`, `JPG`, `WEBP`, `SVG`, `AVIF` vb.).
- `documentOnly()`: Belge formatlarını doğrular (MIME enumu üzerinden `PDF`, `DOC`, `DOCX`, `XLS`, `XLSX`, `PPT`,
  `PPTX`, `TXT`, `RTF`).
- `archiveOnly()`: Sıkıştırılmış arşiv formatlarını doğrular (MIME enumu üzerinden `ZIP`, `TAR`, `GZ`, `RAR`, `7Z`).

```php
$rules = RuleSchema::create(
    R::file('music_file')->audioOnly(),
    R::file('user_photo')->imageOnly(),
    R::file('cv_document')->documentOnly(),
    R::file('backup_file')->archiveOnly()
)->getRules();
```

#### **B. Güvenlik ve Format Filtreleri (Security Filters)**

- `alphaDashOrSpace()`: Harf, sayı, tire, alt tire ve boşluk karakterlerini doğrular (Örn: Ad Soyad veya Ünvan alanları
  için idealdir).
- `noHtml()`: Metin içerisinde HTML/XML etiketlerinin bulunmasını engelleyerek XSS saldırılarına karşı koruma sağlar.
- `passwordSecurity()`: Laravel'in güçlü `Password` kural nesnesini (`letters`, `mixedCase`, `symbols`, `uncompromised`)
  akıcı bir şekilde konfigüre etmenizi sağlar.

```php
$rules = RuleSchema::create(
    R::string('display_name')->required()->alphaDashOrSpace(),
    R::string('user_comment')->required()->noHtml('Html etiketleri kullanılamaz.'),
    R::string('password')->required()->passwordSecurity(
        min: 10,
        mixedCase: true,
        symbols: true,
        uncompromised: true // Parolanın sızıntı veritabanlarında yer almadığından emin olur
    )
)->getRules();
```

---

### 7. Akıcı Veritabanı Modifikatörleri (Fluent Database Modifiers)

Laravel'in `Unique` ve `Exists` kurallarını ekledikten sonra, ek SQL filtrelerini builder zincirinden ayrılmadan
doğrudan tanımlayabilirsiniz:

```php
$rules = RuleSchema::create(
    R::string('email')
        ->required()
        ->unique('users') // 'users' tablosunda benzersiz olmalı
        ->where('status', 'active') // status = 'active'
        ->whereNot('role', 'admin') // role != 'admin'
        ->whereNull('banned_at') // banned_at IS NULL
        ->withoutTrashed() // SoftDeletes: Silinmemiş kayıtlar arasında ara
)->getRules();
```

**Desteklenen Veritabanı Modifikatörleri:**

- `where($column, $value)`: Eşitlik koşulu ekler (Closure veya string sütun).
- `whereNot($column, $value)`: Eşit olmama koşulu ekler.
- `whereNull($column)`: `NULL` değer koşulu ekler.
- `whereNotNull($column)`: `NOT NULL` değer koşulu ekler.
- `onlyTrashed()`: Yalnızca silinmiş kayıtları dahil eder (SoftDeletes).
- `withoutTrashed()`: Silinmiş kayıtları arama dışı bırakır (SoftDeletes).

---

### 8. Akıcı Koşullu Kurallar (`when` & `unless`)

Mantıksal koşullara bağlı olarak kural eklemelerini builder zinciri içinden akıcı bir şekilde kontrol edebilirsiniz:

```php
$isPremium = true;

$rules = RuleSchema::create(
    R::string('bio')
        ->nullable()
        ->when($isPremium, fn($rule) => $rule->max(1000)) // Premium ise 1000 karaktere izin ver
        ->unless($isPremium, fn($rule) => $rule->max(100)) // Premium değilse 100 karakter ile sınırla
)->getRules();
```

---

### 9. Özel Kural ve Closure Desteği (`custom`)

Laravel'in yerel validation sınıflarını (örneğin Custom Rule sınıfları) veya inline Closure fonksiyonlarını `custom`
metodu ile akıcı bir şekilde ekleyebilirsiniz:

```php
use App\Rules\ValidTcNo;

$rules = RuleSchema::create(
    R::string('identity_number')
        ->required()
        ->custom(
            new ValidTcNo(),
            fn($attribute, $value, $fail) => str_starts_with($value, '0') ? $fail('TC kimlik numarası 0 ile başlayamaz.') : null
        )
)->getRules();
```

---

### 10. Livewire ve Filament Entegrasyonu

Bileşen sınıflarınıza `InteractsWithRuleSchema` trait'ini ekleyin ve `ruleSchema()` metodunu tanımlayın. Paket, Laravel
kurallarını ve özelleştirilmiş kural hata mesajlarını otomatik olarak bileşene bağlar:

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

    // Kuralları ve şemayı bu metotta tanımlayın:
    public function ruleSchema(): RuleSchema
    {
        return RuleSchema::create(
            R::string('name')->required()->max(50),
            R::email(), // Hazır kısayol kuralı
            R::phoneNumber('phone') // Hazır telefon kısayol kuralı
        );
    }

    public function submit()
    {
        // Bileşendeki property'leri şemadaki kurallara göre otomatik doğrular.
        $this->validate(); 
        
        // Kaydetme işlemleri...
    }
}
```

---

### 11. Hazır Şemalar ve Kısayol Kurallar

Kod tekrarını azaltmak ve sık yapılan tanımlamaları tek satıra düşürmek için birçok hazır kural ve şema pakete dahildir.

#### **A. Hazır Şemalar (Aggregated Schemas)**

Birden fazla alanı kapsayan ve sıkça kullanılan form kural kümelerini tek satırda yükler:

- `R::login(bool $remember = true)`: E-posta, şifre ve isteğe bağlı beni hatırla alanlarını hazırlar.
- `R::register(bool $passwordConfirmation = true)`: Ad soyad, e-posta, şifre ve şifre onayı alanlarını doğrular.
- `R::resetPassword()`: Şifre sıfırlama tokeni, e-posta ve yeni şifre onay kurallarını içerir.
- `R::updatePassword()`: Mevcut şifreyi güncellemek için şifre doğrulama kurallarını yükler.
- `R::contact()`: İsim, e-posta, mesaj ve onay kutusu (checkbox) kurallarını yükler.
- `R::feedback()`: İsim, e-posta, mesaj ve 1-5 arası puanlama (rating) kurallarını yükler.

*Örnek: Login şemasını çağırıp genişletme:*

```php
$rules = RuleSchema::create(
    RuleSchema::login(), // E-posta ve şifre kuralları otomatik olarak şemaya eklenir.
    R::string('captcha_token')->required() // Captcha gibi ek alanları kolayca ekleyin.
)->getRules();
```

#### **B. Hazır Kısayol Kurallar (Field Shortcuts)**

Sık kullanılan kural zincirlerini tek adımda tanımlamanızı sağlar:

- `R::name($attribute = 'name', ...)`: Standart ad soyad alanı kuralları.
- `R::email($attribute = 'email')`: E-posta formatı.
- `R::password($attribute = 'password')`: Minimum 8 karakterli şifre kuralı.
- `R::phoneNumber($attribute = 'phone_number')`: Regex doğrulamalı telefon numarası kuralı.
- `R::postalCode($attribute = 'postal_code')`: Posta kodu kuralı.
- `R::uuid($attribute = 'uuid')`: UUID format doğrulaması.
- `R::ulid($attribute = 'ulid')`: ULid format doğrulaması.
- `R::url($attribute = 'url')`: URL doğrulaması (http/https).
- `R::image($attribute = 'image')`: Resim dosyası doğrulaması (boyut ve tip sınırlamalı).
- `R::images($attribute = 'images', ?callable $callback = null)`: Çoklu resim doğrulaması (dizi).
- `R::files($attribute = 'files', ?callable $callback = null)`: Çoklu dosya doğrulaması (dizi).
- `R::text($attribute = 'text')`: Standart metin alanı kuralı (16K karaktere kadar).
- `R::longText($attribute = 'long_text')`: Uzun metin alanı kuralı (65K karaktere kadar).

---

### 12. Doğrudan Tekil Değişken ve Değer Doğrulama (Direct Value Validation)

Herhangi bir veri dizisini veya tekil bir değişken değerini doğrudan bir kural builder nesnesi üzerinden pratik bir
şekilde doğrulamak için `validate($value)`, `passes($value)` ve `fails($value)` yardımcıları kullanılabilir. Form
Request veya dizi sarmalama (wrapper) zorunluluğunu ortadan kaldırarak kodun esnekliğini artırır:

```php
$email = 'not-an-email';

// 1. Boolean Kontrolü
if (R::string()->email()->fails($email)) {
    // Geçersiz e-posta durumu...
}

if (R::string()->email()->passes('test@example.com')) {
    // Geçerli e-posta durumu...
}

// 2. Doğrulama ve Değer Alma (Hata durumunda ValidationException fırlatır)
try {
    $validatedEmail = R::string()->email()->validate('user@domain.com');
} catch (\Illuminate\Validation\ValidationException $e) {
    $errors = $e->errors();
}

// 3. İç İçe Geçmiş Dizi / JSON Doğrulama
$profileData = [
    'name' => 'Alpay',
    'email' => 'alpay@example.com'
];

$profileRule = R::array()->children([
    R::string('name')->required(),
    R::string('email')->required()->email()
]);

if ($profileRule->passes($profileData)) {
    // Profil verisi geçerli...
}
```

---

### 13. Doğrudan Dizi Doğrulama (`validate`)

Şemaları sadece HTTP isteklerinde değil, herhangi bir PHP dizisini doğrulamak için de kullanabilirsiniz. (Örn: Job
kuyrukları, Console komutları, API Client responses):

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
    
    // Doğrulama başarılı ise filtrelenmiş $validated dizisi döner.
} catch (\Illuminate\Validation\ValidationException $e) {
    // Hataları yakala
    $errors = $e->errors();
}
```

---

### 14. Çok Adımlı Form Sihirbazı (`MultiStepSchema`)

Adımlı (wizard) form yapılarınızdaki kural setlerini adım numarasına göre dinamik olarak yönetin:

```php
use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use Alpayklncrsln\RuleSchema\R;

// HTTP isteğindeki 'step' parametresine göre sadece o adıma ait kuralları döndürür:
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

### 15. Modelden Otomatik Kural Üretimi (`RuleSchema::model`)

Veritabanı tablonuzu ve Eloquent modelinizi analiz ederek, sütun tiplerine ve boş geçilebilirlik (nullable) durumlarına
göre kuralları otomatik derler:

```php
use App\Models\Product;
use Alpayklncrsln\RuleSchema\RuleSchema;

// Product modelinin veritabanındaki şemasına göre kuralları otomatik oluşturur:
$rules = RuleSchema::model(Product::class)->getRules();
```

---

### 16. Performans & Hafıza Optimizasyonu (Compilation Memoization)

Karmaşık form yapılarında ve çok seviyeli iç içe dizilerde (`children` & `each` içeren derin ağaçlar), kuralların
sürekli özyinelemeli olarak derlenmesi CPU ve bellek üzerinde ekstra yük oluşturabilir.

Rule Schema, `BaseRuleBuilder` sınıfında entegre edilmiş bir **derleme memoization (caching)** yapısına sahiptir.
Tanımlanan kurallar sadece ilk derleme anında bir kez hesaplanır ve hafızada tutulur. Takip eden tüm `getRules()` veya
kural okuma çağrılarında sıfır CPU maliyeti ve sıfır ek bellek kullanımı ile $O(1)$ sürede doğrudan önbellekten sunulur.
Eğer zincir üzerinde yeni bir kural veya hata mesajı mutasyonu yapılırsa önbellek otomatik olarak temizlenir ve kural
yeniden hesaplanır.

---

### 17. Veri Temizleme, Dönüştürme ve Varsayılan Değerler (Data Sanitization & Casting)

Rule Schema, doğrulamanın ötesine geçerek parametrelerinizi inline olarak temizlemenize, cast etmenize ve varsayılan
değerler tanımlamanıza olanak tanır. Doğrulama başarılı olduktan sonra verileriniz otomatik olarak dönüştürülmüş şekilde
döndürülür.

#### **Genel Sanitizer Metotları (Tüm Builder Sınıflarında)**

- `default(mixed $value)`: Giriş verisi `null` ise belirtilen varsayılan değeri atar.
- `sanitize(callable $callback)`: Özel bir temizleme veya dönüştürme closure fonksiyonu çalıştırır.
- `transform(callable $callback)`: `sanitize` metodunun alternatif adıdır (alias).

#### **String-Özgü Sanitizer Metotları (`R::string()`)**

- `trim()`: Metnin başındaki ve sonundaki boşlukları temizler.
- `lower()`: Metni çoklu dil uyumlu (mb-safe) olarak küçük harfe dönüştürür.
- `upper()`: Metni çoklu dil uyumlu (mb-safe) olarak büyük harfe dönüştürür.
- `stripTags()`: HTML ve PHP etiketlerini temizler (XSS koruması için idealdir).
- `slug()`: Metni URL uyumlu bir slug ifadesine dönüştürür.

#### **Numeric-Özgü Casting Metotları (`R::numeric()`)**

- `castToInt()`: Sayısal değeri PHP `int` veri tipine dönüştürür.
- `castToFloat()`: Sayısal değeri PHP `float` veri tipine dönüştürür.

#### **Date-Özgü Casting Metotları (`R::date()`)**

- `castToCarbon()`: Tarih değerini doğrudan bir `Carbon\Carbon` nesnesine dönüştürür.

#### **Örnek Kullanım:**

```php
$validatedData = RuleSchema::create(
    R::string('name')->required()->trim()->lower(),
    R::numeric('age')->nullable()->default(18)->castToInt(),
    R::date('published_at')->castToCarbon(),
    R::array('tags')->each(R::string()->trim()->lower())
)->validate($request->all());

// $validatedData['name'] -> '  AHMET  ' iken 'ahmet' olur.
// $validatedData['age'] -> null iken 18 (int) olur.
// $validatedData['published_at'] -> '2026-06-21' stringi Carbon nesnesine dönüşür.
// $validatedData['tags'] -> ['  PHP ', ' Laravel '] iken ['php', 'laravel'] olur.
```

---

## 💻 Artisan CLI Komutları

Paket, geliştirme sürecinizi hızlandırmak için çeşitli kod üreteçleri sunar:

### A. Yeni Şema İstek Sınıfı Üretme (`make:rule-schema`)

Laravel Form Request yapısında, Rule Schema entegreli istek sınıfları üretir:

```bash
php artisan make:rule-schema ProductRequest
```

Eğer `--m` parametresi verilirse, ilgili model ismini kullanarak kuralları veritabanı şemasından otomatik türetir ve
dosyanın içerisine yazar:

```bash
php artisan make:rule-schema ProductRequest --m
```

### B. Auth Request Sınıfları Üretme (`rule-schema:auth`)

Kimlik doğrulama süreçlerinde sık kullanılan Form Request sınıflarını (LoginRequest, RegisterRequest, vb.) saniyeler
içinde otomatik olarak üretir:

```bash
# Tüm Auth Request sınıflarını tek seferde üretir:
php artisan rule-schema:auth all

# Sadece belirli bir auth sınıfı üretmek için:
php artisan rule-schema:auth login
php artisan rule-schema:auth register
```

---

## 🧪 Testler

Paket, %100 test kapsama oranına (test coverage) sahiptir. Pest PHP ile yazılmış 397 adet testi çalıştırmak için:

```bash
composer test
```

Statik tip analizi doğrulamaları için PHPStan analizi çalıştırın:

```bash
composer analyse
```

---

## 📄 Lisans

Bu paket [MIT Lisansı](LICENSE.md) kapsamında lisanslanmıştır. Detaylı bilgi için lisans dosyasını inceleyebilirsiniz.
