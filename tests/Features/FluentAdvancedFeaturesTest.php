<?php

use Alpayklncrsln\RuleSchema\Default\MultiStepSchema;
use Alpayklncrsln\RuleSchema\R;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Alpayklncrsln\RuleSchema\Traits\InteractsWithRuleSchema;
use Illuminate\Validation\Rules\Unique;

test('unless conditional method works', function () {
    $rulesTrue = RuleSchema::create(
        R::string('username')->unless(true, function ($rule) {
            $rule->min(5);
        }, function ($rule) {
            $rule->min(3);
        })
    )->getRules();

    expect($rulesTrue['username'])->toBe(['string', 'min:3']);

    $rulesFalse = RuleSchema::create(
        R::string('username')->unless(false, function ($rule) {
            $rule->min(5);
        }, function ($rule) {
            $rule->min(3);
        })
    )->getRules();

    expect($rulesFalse['username'])->toBe(['string', 'min:5']);
});

test('fluent database unique and exists modifiers work', function () {
    $rules = RuleSchema::create(
        R::string('email')->unique('users')->where('status', 'active')->whereNull('deleted_at')
    )->getRules();

    expect($rules['email'])->toBeArray();
    expect($rules['email'][0])->toBe('string');
    expect($rules['email'][1])->toBeInstanceOf(Unique::class);

    /** @var Unique $uniqueRule */
    $uniqueRule = $rules['email'][1];

    $compiledString = (string)$uniqueRule;
    expect($compiledString)->toContain('unique:users');
    expect($compiledString)->toContain('status');
    expect($compiledString)->toContain('active');
    expect($compiledString)->toContain('deleted_at');
});

test('interacts with rule schema trait works', function () {
    $component = new class {
        use InteractsWithRuleSchema;

        public function ruleSchema(): RuleSchema
        {
            return RuleSchema::create(
                R::string('name')->required()
            );
        }
    };

    expect($component->rules())->toBe([
        'name' => ['string', 'required'],
    ]);
});

test('fluent pre-defined rules and schemas work', function () {
    $emailRule = R::email('user_email')->getRule();
    expect($emailRule)->toBe([
        'user_email' => ['required', 'email'],
    ]);

    $loginSchema1 = R::login(false)->getRules();
    $loginSchema2 = RuleSchema::login(false)->getRules();

    expect($loginSchema1)->toBe([
        'email' => ['required', 'email', 'max:255', 'exists:users,email'],
        'password' => ['required', 'min:8', 'max:255'],
    ]);

    expect($loginSchema2)->toBe($loginSchema1);
});

test('fluent builder integration in multistep and cache', function () {
    $rules = MultiStepSchema::make('step', true)
        ->step(1, R::string('name')->required())
        ->step(2, R::numeric('age')->required())
        ->getRules();

    expect($rules)->toBe([
        'step_1.name' => ['string', 'required'],
        'step_2.age' => ['numeric', 'required'],
    ]);

    $cached = RuleSchema::cache('test_cache_key', 60, R::string('name')->required());
    expect($cached->getRules())->toBe([
        'name' => ['string', 'required'],
    ]);
});
