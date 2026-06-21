<?php

use Alpayklncrsln\RuleSchema\BaseRuleBuilder;
use Alpayklncrsln\RuleSchema\Enums\FileMime;
use Alpayklncrsln\RuleSchema\R;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

test('type safe string rule building', function () {
    $rules = RuleSchema::create(
        R::string('email')->required()->email()->max(100)
    )->getRules();

    expect($rules)->toBe([
        'email' => ['string', 'required', 'email', 'max:100'],
    ]);
});

test('type safe numeric rule building', function () {
    $rules = RuleSchema::create(
        R::numeric('age')->required()->integer()->min(18)
    )->getRules();

    expect($rules)->toBe([
        'age' => ['numeric', 'required', 'integer', 'min:18'],
    ]);
});

test('type safe date rule building', function () {
    $rules = RuleSchema::create(
        R::date('birthday')->required()->dateFormat('Y-m-d')
    )->getRules();

    expect($rules)->toBe([
        'birthday' => ['date', 'required', 'date_format:Y-m-d'],
    ]);
});

test('type safe file rule building', function () {
    $rules = RuleSchema::create(
        R::file('avatar')->required()->image()->mimes(null, 'jpg', 'png')->max(2048)->min(100)->size(500)
    )->getRules();

    expect($rules)->toBe([
        'avatar' => ['file', 'required', 'image', 'mimes:jpg,png', 'max:2048', 'min:100', 'size:500'],
    ]);
});

test('nested array validation with children', function () {
    $rules = RuleSchema::create(
        R::array('user')->required()->children([
            R::string('name')->required(),
            R::string('email')->required()->email(),
        ])
    )->getRules();

    expect($rules)->toBe([
        'user' => ['array', 'required'],
        'user.name' => ['string', 'required'],
        'user.email' => ['string', 'required', 'email'],
    ]);
});

test('nested array validation with each', function () {
    $rules = RuleSchema::create(
        R::array('tags')->required()->each(
            R::string()->required()->min(3)
        )
    )->getRules();

    expect($rules)->toBe([
        'tags' => ['array', 'required'],
        'tags.*' => ['string', 'required', 'min:3'],
    ]);
});

test('complex nested array validation', function () {
    $rules = RuleSchema::create(
        R::array('invoice')->children([
            R::string('number')->required(),
            R::array('items')->each(
                R::array()->children([
                    R::string('name')->required(),
                    R::numeric('price')->required()->min(1),
                ])
            ),
        ])
    )->getRules();

    expect($rules)->toBe([
        'invoice' => ['array'],
        'invoice.number' => ['string', 'required'],
        'invoice.items' => ['array'],
        'invoice.items.*' => ['array'],
        'invoice.items.*.name' => ['string', 'required'],
        'invoice.items.*.price' => ['numeric', 'required', 'min:1'],
    ]);
});

test('fluent builder custom rules', function () {
    $rules = RuleSchema::create(
        R::string('username')->custom('alpha_dash', new Enum(FileMime::class))
    )->getRules();

    expect($rules['username'])->toBeArray();
    expect($rules['username'][0])->toBe('string');
    expect($rules['username'][1])->toBe('alpha_dash');
    expect($rules['username'][2])->toBeInstanceOf(Enum::class);
});

test('fluent builder conditional when rule', function () {
    $rulesTrue = RuleSchema::create(
        R::string('username')->when(true, function ($rule) {
            $rule->min(5);
        }, function ($rule) {
            $rule->min(3);
        })
    )->getRules();

    expect($rulesTrue['username'])->toBe(['string', 'min:5']);

    $rulesFalse = RuleSchema::create(
        R::string('username')->when(false, function ($rule) {
            $rule->min(5);
        }, function ($rule) {
            $rule->min(3);
        })
    )->getRules();

    expect($rulesFalse['username'])->toBe(['string', 'min:3']);
});

test('builder macro extensions work', function () {
    BaseRuleBuilder::macro('customUppercaseLength', function (int $length) {
        return $this->uppercase()->max($length);
    });

    $rules = RuleSchema::create(
        R::string('code')->customUppercaseLength(10)
    )->getRules();

    expect($rules['code'])->toBe(['string', 'uppercase', 'max:10']);
});

test('backed enum validation helpers work', function () {
    $rules = RuleSchema::create(
        R::string('file_mime')->inEnum(FileMime::class),
        R::string('not_file_mime')->notInEnum(FileMime::class)
    )->getRules();

    expect($rules['file_mime'][1])->toBeInstanceOf(Enum::class);
    expect($rules['not_file_mime'][1])->toBe('not_in:aac,abw,apng,arc,avif,avi,azw,bin,bmp,bz,bz2,cda,csh,css,csv,doc,docx,eot,epub,gz,gif,htm,html,ico,ics,jar,jpeg,jpg,js,json,jsonld,mid,midi,mjs,mp3,mp4,mpeg,mpkg,odp,ods,odt,oga,ogv,ogx,opus,otf,png,pdf,php,ppt,pptx,rar,rtf,sh,svg,tar,tiff,ts,ttf,txt,vsd,wav,weba,webm,webp,woff,woff2,xhtml,xls,xlsx,xml,xul,xz,zip,3gp,3g2,7z');
});

test('file type enum group shortcuts work', function () {
    $rules = RuleSchema::create(
        R::file('audio')->audioOnly(),
        R::file('image')->imageOnly(),
        R::file('doc')->documentOnly(),
        R::file('archive')->archiveOnly()
    )->getRules();

    expect($rules['audio'][1])->toBe('mimes:aac,mid,midi,mp3,opus,oga,wav,weba');
    expect($rules['image'][1])->toBe('mimes:png,jpg,jpeg,gif,svg,webp,avif,bmp,ico,tiff,apng');
    expect($rules['doc'][1])->toBe('mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf');
    expect($rules['archive'][1])->toBe('mimes:zip,tar,gz,rar,7z');
});

test('string security and format rules work', function () {
    $rules = RuleSchema::create(
        R::string('username')->alphaDashOrSpace(),
        R::string('comment')->noHtml(),
        R::string('password')->passwordSecurity(mixedCase: true, symbols: true)
    )->getRules();

    expect($rules['username'][1])->toBe('regex:/^[\pL\pM\pN_-]+(?:\s+[\pL\pM\pN_-]+)*$/u');
    expect($rules['comment'][1])->toBe('not_regex:/<[^>]*>/');
    expect($rules['password'][1])->toBeInstanceOf(Password::class);
});

test('multiple images and files shortcuts work', function () {
    $rules = RuleSchema::create(
        R::images('gallery', function ($file) {
            $file->max(4096)->mimes(null, 'jpg', 'png');
        })->min(1)->max(5),
        R::files('attachments')->max(3)
    )->getRules();

    expect($rules)->toBe([
        'gallery' => ['array', 'min:1', 'max:5'],
        'gallery.*' => ['file', 'image', 'max:4096', 'mimes:jpg,png'],
        'attachments' => ['array', 'max:3'],
        'attachments.*' => ['file'],
    ]);
});

test('builder direct validation works', function () {
    $emailRule = R::string()->email();

    expect($emailRule->passes('test@example.com'))->toBeTrue();
    expect($emailRule->fails('invalid-email'))->toBeTrue();

    $validated = $emailRule->validate('test@example.com');
    expect($validated)->toBe('test@example.com');

    // Throws ValidationException if fails
    expect(fn() => $emailRule->validate('invalid-email'))->toThrow(ValidationException::class);
});
