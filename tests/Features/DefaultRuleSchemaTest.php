<?php

use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

test('login', function () {
    $rule = DefaultRuleSchema::login()->getRules();
    expect($rule['email'])->toBeArray()
        ->toBeArray('email')
        ->toBeArray('exists:users,email')
        ->toBeArray('required')
        ->and($rule['password'])->toBeArray()
        ->toBeArray('min:8')
        ->toBeArray('required');
});

test('register', function () {
    $rule = DefaultRuleSchema::register()->getRules();
    expect($rule['name'])->toBeArray()
        ->toBeArray('max')
        ->toBeArray('required')
        ->and($rule['email'])->toBeArray()
        ->toBeArray('email')
        ->toBeArray('max')
        ->toBeArray('required')
        ->toBeArray('unique:users,email')
        ->and($rule['password'])->toBeArray()
        ->toBeArray('confirmed')
        ->toBeArray('min:8')
        ->toBeArray('required');
});

test('resetPassword', function () {
    $rule = DefaultRuleSchema::resetPassword()->getRules();
    expect($rule['email'])->toBeArray()
        ->toBeArray('email')
        ->toBeArray('exists:users,email')
        ->toBeArray('required')
        ->and($rule['token'])->toBeArray()
        ->toBeArray('exists:password_resets,token')
        ->toBeArray('required')
        ->and($rule['password'])->toBeArray()
        ->toBeArray('confirmed')
        ->toBeArray('min:8')
        ->toBeArray('required');
});

test('updatePassword', function () {
    $rule = DefaultRuleSchema::updatePassword()->getRules();
    expect($rule['password'])->toBeArray()
        ->toBeArray('confirmed')
        ->toBeArray('min:8')
        ->toBeArray('required');
});

test('contact', function () {
    $rule = DefaultRuleSchema::contact()->getRules();
    expect($rule['name'])->toBeArray()
        ->toBeArray('max')
        ->toBeArray('required')
        ->and($rule['email'])->toBeArray()
        ->toBeArray('email')
        ->toBeArray('max')
        ->toBeArray('required')
        ->and($rule['message'])->toBeArray()
        ->toBeArray('max:800')
        ->toBeArray('required')
        ->and($rule['isRead'])->toBeArray()
        ->toBeArray('accepted')
        ->toBeArray('required');
});

test('feedback', function () {
    $rule = DefaultRuleSchema::feedback()->getRules();
    expect($rule['name'])->toBeArray()
        ->toBeArray('max')
        ->toBeArray('required')
        ->and($rule['email'])->toBeArray()
        ->toBeArray('email')
        ->toBeArray('max')
        ->toBeArray('required')
        ->and($rule['message'])->toBeArray()
        ->toBeArray('max:800')
        ->toBeArray('required')
        ->and($rule['rating'])->toBeArray()
        ->toBeArray('numeric')
        ->toBeArray('between:1,5')
        ->toBeArray('required');
});
