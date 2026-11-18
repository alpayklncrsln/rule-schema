<?php

use Alpayklncrsln\RuleSchema\Default\DefaultRule;
use Alpayklncrsln\RuleSchema\Default\PhoneNumberRule;
use Alpayklncrsln\RuleSchema\Enums\FileMime;
use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule as LaravelRule;

// 1. email() flags

test('email flags are comma separated', function (array $args, array $expected) {
    expect(Rule::make('email')->email(...$args)->getRule())->toBe(['email' => $expected]);
})->with([
    'no flags' => [[], ['email']],
    'dns' => [[true], ['email:dns']],
    'dns + rfc' => [[true, true], ['email:rfc,dns']],
    'all flags' => [[true, true, true, true], ['email:rfc,strict,dns,spoof']],
    'extra only' => [['extra' => 'filter'], ['email:filter']],
    'rfc + extra' => [['rfcCheck' => true, 'extra' => 'filter'], ['email:rfc,filter']],
]);

test('email with flags is accepted by the validator', function () {
    $rules = Rule::make('email')->email(rfcCheck: true, strictCheck: true, extra: 'filter')->getRule();

    expect($rules)->toBe(['email' => ['email:rfc,strict,filter']])
        ->and(Validator::make(['email' => 'admin@example.com'], $rules)->passes())->toBeTrue()
        ->and(Validator::make(['email' => 'not-an-email'], $rules)->passes())->toBeFalse();
});

// 2. mimeAndMimetypes()

test('mimeAndMimetypes keeps every mime value', function () {
    $rule = Rule::make('file')->mimeAndMimetypes(null, FileMime::PNG, FileMime::JPG);

    expect($rule->getRule())->toBe(['file' => [
        'mimes:'.FileMime::PNG->getValue().','.FileMime::JPG->getValue(),
        'mimetypes:'.FileMime::PNG->type().','.FileMime::JPG->type(),
    ]])->and($rule->getMessage())->toBe([]);
});

test('mimeAndMimetypes registers the message under keys laravel knows', function () {
    $rule = Rule::make('file')->mimeAndMimetypes('MSG', FileMime::PNG);

    expect($rule->getMessage())->toBe(['file.mimes' => 'MSG', 'file.mimetypes' => 'MSG']);
});

// 3. rule()

test('rule passes string rules through untouched', function () {
    expect(Rule::make('name')->rule('required')->rule('max:10')->getRule())
        ->toBe(['name' => ['required', 'max:10']]);
});

test('rule passes object rules through untouched', function () {
    $in = LaravelRule::in(['a', 'b']);
    $closure = function (string $attribute, mixed $value, Closure $fail) {
        if ($value === 'c') {
            $fail('NOPE');
        }
    };

    $rules = Rule::make('name')->required()->rule($in)->rule($closure)->getRule();

    expect($rules['name'])->toBe(['required', $in, $closure])
        ->and(Validator::make(['name' => 'a'], $rules)->passes())->toBeTrue()
        ->and(Validator::make(['name' => 'z'], $rules)->passes())->toBeFalse();
});

// 4. doesnt_start_with / doesnt_end_with

test('doesntStartWith produces the laravel rule name', function () {
    $rule = Rule::make('name')->doesntStartWith(['admin', 'root'], 'MSG');

    expect($rule->getRule())->toBe(['name' => ['doesnt_start_with:admin,root']])
        ->and($rule->getMessage())->toBe(['name.doesnt_start_with' => 'MSG'])
        ->and(Validator::make(['name' => 'admin1'], $rule->getRule(), $rule->getMessage())->errors()->first('name'))->toBe('MSG');
});

test('doesntEndWith produces the laravel rule name', function () {
    $rule = Rule::make('name')->doesntEndWith(['admin', 'root'], 'MSG');

    expect($rule->getRule())->toBe(['name' => ['doesnt_end_with:admin,root']])
        ->and($rule->getMessage())->toBe(['name.doesnt_end_with' => 'MSG'])
        ->and(Validator::make(['name' => 'xadmin'], $rule->getRule(), $rule->getMessage())->errors()->first('name'))->toBe('MSG');
});

// 5. dateTime()

test('dateTime produces a rule laravel knows', function () {
    $rule = Rule::make('published_at')->dateTime('MSG');

    expect($rule->getRule())->toBe(['published_at' => ['date']])
        ->and($rule->getMessage())->toBe(['published_at.date' => 'MSG'])
        ->and(Validator::make(['published_at' => '2026-01-02 10:20:30'], $rule->getRule())->passes())->toBeTrue()
        ->and(Validator::make(['published_at' => 'nope'], $rule->getRule(), $rule->getMessage())->errors()->first('published_at'))->toBe('MSG');
});

test('model schema validates datetime and timestamp columns', function () {
    Schema::create('regression_events', function (Blueprint $table) {
        $table->id();
        $table->dateTime('starts_at');
        $table->timestamp('ends_at');
    });

    $rules = RuleSchema::model('regression_events')->getRules();

    expect($rules['starts_at'])->toContain('date')->not->toContain('datetime')
        ->and($rules['ends_at'])->toContain('date')->not->toContain('datetime')
        ->and(Validator::make(['starts_at' => '2026-01-02 10:20:30', 'ends_at' => '2026-01-03 10:20:30'], $rules)->passes())->toBeTrue();
});

// 6. message keys

test('custom message keys use the laravel rule name', function (Closure $build, string $key) {
    expect($build(Rule::make('f'))->getMessage())->toBe(['f.'.$key => 'MSG']);
})->with([
    'acceptedIf' => [fn (Rule $r) => $r->acceptedIf('a', 'b', 'MSG'), 'accepted_if'],
    'activeUrl' => [fn (Rule $r) => $r->activeUrl(true, 'MSG'), 'active_url'],
    'notRegex' => [fn (Rule $r) => $r->notRegex('/a/', 'MSG'), 'not_regex'],
    'macAddress' => [fn (Rule $r) => $r->macAddress(true, 'MSG'), 'mac_address'],
    'multipleOf' => [fn (Rule $r) => $r->multipleOf('2', 'MSG'), 'multiple_of'],
    'missingIf' => [fn (Rule $r) => $r->missingIf('a', 'b', 'MSG'), 'missing_if'],
    'notIn' => [fn (Rule $r) => $r->notIn(['a'], 'MSG'), 'not_in'],
    'presentWithAll' => [fn (Rule $r) => $r->presentWithAll(['a'], 'MSG'), 'present_with_all'],
    'requiredWithoutAll' => [fn (Rule $r) => $r->requiredWithoutAll(['a'], 'MSG'), 'required_without_all'],
    'startsWith' => [fn (Rule $r) => $r->startsWith(['a'], 'MSG'), 'starts_with'],
    'image' => [fn (Rule $r) => $r->image(message: 'MSG'), 'image'],
]);

test('every method registers its message under a rule it actually produces', function () {
    $args = fn (ReflectionMethod $method) => array_map(fn (ReflectionParameter $p) => match (true) {
        $p->getName() === 'message' => 'MSG',
        $p->isVariadic() => FileMime::PNG,
        $p->isDefaultValueAvailable() => $p->getDefaultValue(),
        (string) $p->getType() === 'array' => ['a'],
        (string) $p->getType() === 'int' => 1,
        default => 'a',
    }, $method->getParameters());

    $skip = ['make', 'rule', 'getRule', 'getMessage', 'getAttribute', 'enum', '__construct'];

    foreach ((new ReflectionClass(Rule::class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if (in_array($method->getName(), $skip) || ! in_array('message', array_map(fn ($p) => $p->getName(), $method->getParameters()))) {
            continue;
        }

        $rule = Rule::make('f');
        $method->invokeArgs($rule, $args($method));
        $produced = array_map(fn ($r) => 'f.'.explode(':', $r)[0], $rule->getRule()['f']);

        expect($rule->getMessage())->not->toBe([], $method->getName().' registers no message');
        foreach (array_keys($rule->getMessage()) as $key) {
            expect($produced)->toContain($key);
        }
    }
});

test('camelCase method message reaches the validator', function () {
    $rule = Rule::make('f')->acceptedIf('a', 'b', 'MSG');

    expect(Validator::make(['f' => 'no', 'a' => 'b'], $rule->getRule(), $rule->getMessage())->errors()->first('f'))->toBe('MSG');
});

// 7. merge()

test('create accepts a nested RuleSchema', function () {
    $inner = RuleSchema::create(Rule::make('name')->required(true, 'MSG'));
    $schema = RuleSchema::create($inner, Rule::make('age')->integer());

    expect($schema->getRules())->toBe(['name' => ['required'], 'age' => ['integer']])
        ->and($schema->getMessages())->toBe(['name.required' => 'MSG']);
});

test('merge accepts a RuleSchema', function () {
    $schema = RuleSchema::create(Rule::make('age')->integer())
        ->merge(RuleSchema::create(Rule::make('name')->required()));

    expect($schema->getRules())->toBe(['age' => ['integer'], 'name' => ['required']]);
});

// 8. phone number

test('phone number rules compare length, not numeric value', function (Rule $rule) {
    $rules = $rule->getRule();

    expect($rules['phone_number'])->toContain('string', 'min:10', 'max:15')->not->toContain('numeric')
        ->and(Validator::make(['phone_number' => '5551234567'], $rules)->passes())->toBeTrue()
        ->and(Validator::make(['phone_number' => '+90 5551234567'], $rules)->passes())->toBeTrue()
        ->and(Validator::make(['phone_number' => '12345'], $rules)->passes())->toBeFalse();
})->with([
    'DefaultRule::phoneNumber' => [fn () => DefaultRule::phoneNumber()],
    'PhoneNumberRule::general' => [fn () => (new PhoneNumberRule('phone_number'))->general()],
]);
