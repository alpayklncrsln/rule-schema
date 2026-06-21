<?php

use Alpayklncrsln\RuleSchema\R;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

test('base sanitizers and custom transformation', function () {
    $schema = RuleSchema::create(
        R::string('username')->required()->trim()->lower(),
        R::string('display_name')->transform(fn($val) => 'User: ' . $val),
        R::numeric('points')->nullable()->default(100)->castToInt()
    );

    $validated = $schema->validate([
        'username' => '   ALPAY  ',
        'display_name' => 'Kilinçarslan',
        'points' => null,
    ]);

    expect($validated['username'])->toBe('alpay')
        ->and($validated['display_name'])->toBe('User: Kilinçarslan')
        ->and($validated['points'])->toBe(100);
});

test('string sanitizers - trim, lower, upper, stripTags, slug', function () {
    $schema = RuleSchema::create(
        R::string('title')->trim()->upper(),
        R::string('bio')->stripTags(),
        R::string('slug_title')->slug()
    );

    $validated = $schema->validate([
        'title' => '   cool title   ',
        'bio' => '<p>Hello <b>World</b></p>',
        'slug_title' => 'My Awesome Blog Post',
    ]);

    expect($validated['title'])->toBe('COOL TITLE')
        ->and($validated['bio'])->toBe('Hello World')
        ->and($validated['slug_title'])->toBe('my-awesome-blog-post');
});

test('numeric sanitizers - castToInt, castToFloat', function () {
    $schema = RuleSchema::create(
        R::numeric('age')->castToInt(),
        R::numeric('price')->castToFloat()
    );

    $validated = $schema->validate([
        'age' => '32',
        'price' => '19.99',
    ]);

    expect($validated['age'])->toBe(32)
        ->and($validated['age'])->toBeInt()
        ->and($validated['price'])->toBe(19.99)
        ->and($validated['price'])->toBeFloat();
});

test('date sanitizers - castToCarbon', function () {
    $schema = RuleSchema::create(
        R::date('created_at')->castToCarbon()
    );

    $validated = $schema->validate([
        'created_at' => '2026-06-21 15:30:00',
    ]);

    expect($validated['created_at'])->toBeInstanceOf(Carbon::class)
        ->and($validated['created_at']->format('Y-m-d H:i:s'))->toBe('2026-06-21 15:30:00');
});

test('nested arrays with children and each sanitization', function () {
    $schema = RuleSchema::create(
        R::array('user')->children([
            R::string('name')->trim()->upper(),
            R::array('skills')->each(
                R::string()->trim()->lower()
            ),
        ])
    );

    $validated = $schema->validate([
        'user' => [
            'name' => '   john doe   ',
            'skills' => ['  PHP  ', '  LARAVEL '],
        ],
    ]);

    expect($validated['user']['name'])->toBe('JOHN DOE')
        ->and($validated['user']['skills'])->toBe(['php', 'laravel']);
});

test('builder direct validation with sanitization', function () {
    $builder = R::string()->trim()->lower();
    $result = $builder->validate('   HELLO   ');
    expect($result)->toBe('hello');
});

test('caching schemas containing closures and sanitizers', function () {
    // Clear cache first
    Cache::forget('my-test-cache');

    $schema = RuleSchema::cache('my-test-cache', 10,
        R::string('email')->required()->trim()->lower()
    );

    // Call getRules to trigger setCacheData
    $schema->getRules();

    // Retrieve from cache
    $cachedSchema = Cache::get('my-test-cache');

    expect($cachedSchema)->toBeInstanceOf(RuleSchema::class);

    $validated = $cachedSchema->validate([
        'email' => '   TEST@EXAMPLE.COM   ',
    ]);

    expect($validated['email'])->toBe('test@example.com');
});
