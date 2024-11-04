<?php

use Alpayklncrsln\RuleSchema\Rule;

test('get attribute of rule ', function () {
    $rule = Rule::make('name');

    expect($rule)->toBeInstanceOf(Rule::class)
        ->and($rule)
        ->getAttribute()
        ->toBe('name')
        ->toBeString();
});

test('rule accepted check', function () {
    $rule = Rule::make('name')->accepted()
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('accepted');
});

test('active_url ', function () {
    $rule = Rule::make('name')->activeUrl()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('active_url');
});

test('rule acceptedIf check', function () {
    $rule = Rule::make('name')->acceptedIf('field', 'value')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('accepted_if');
});

test('rule after check', function () {
    $rule = Rule::make('name')->after('2020-01-01')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('after');
});

test('rule afterDate check', function () {
    $rule = Rule::make('name')->afterDate('2020-01-01')
        ->getRule();
    expect($rule['name'])
        ->toBeArray()
        ->toBeArray('after')
        ->toBeArray('date');
});

test('rule afterDateYesterday check', function () {
    $rule = Rule::make('date')->afterDateYesterday()->getRule();

    expect($rule['date'])
        ->toBeArray()
        ->toBeArray('after:yesterday')
        ->toBeArray('date');
});

test('rule afterDateTomorrow check', function () {
    $rule = Rule::make('date')->afterDateTomorrow()->getRule();

    expect($rule['date'])
        ->toBeArray()
        ->toBeArray('after:tomorrow')
        ->toBeArray('date');
});

test('rule alpha check', function () {
    $rule = Rule::make('name')->alpha('ascii')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha:ascii');
});

test('rule alphaNumeric check', function (string $value) {
    $rule = Rule::make('name')->alphaNumeric($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha_num:'.$value);
})->with([
    'ascii',
]);

test('rule alphaDash check', function (string $value) {
    $rule = Rule::make('name')->alphaDash($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha_dash:'.$value);
})->with([
    'ascii',
]);

test('array check', function () {
    $rule = Rule::make('content')->array()->getRule();
    expect($rule['content'])->toBeArray()
        ->toBeArray('array');
});
test('rule bail', function () {
    $rule = Rule::make('name')->bail()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('bail');
});

test('rule before', function () {
    $rule = Rule::make('date')->before('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before');
});

test('rule beforeDate', function () {
    $rule = Rule::make('date')->beforeDate('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before')
        ->toBeArray('date');
});

test('rule beforeDateYesterday', function () {
    $rule = Rule::make('date')->beforeDateYesterday()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before:yesterday')
        ->toBeArray('date');
});

test('rule beforeDateTomorrow', function () {
    $rule = Rule::make('date')->beforeDateTomorrow()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before:tomorrow')
        ->toBeArray('date');
});

test('rule between', function () {
    $rule = Rule::make('name')->between(1, 10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('between:1,10');
});

test('rule boolean', function () {
    $rule = Rule::make('is_admin')->boolean()->getRule();
    expect($rule['is_admin'])->toBeArray()
        ->toBeArray('boolean');
});

test('rule confirmed', function () {
    $rule = Rule::make('password')->confirmed()->getRule();
    expect($rule['password'])->toBeArray()
        ->toBeArray('confirmed');
});

test('contains', function (string $value) {
    $rule = Rule::make('name')->contains($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('contains:'.$value);
})->with([
    'test',
    'test123',
    'test 123',
    'test 123 test',
]);

test('currentPassword', function (bool|string $guard) {
    $rule = Rule::make('name')->currentPassword($guard)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('current_password:'.$guard);
})->with([
    'web',
    'api',
    'admin',
    'user',
    true
]);

test('date', function () {
    $rule = Rule::make('date')->date()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('date');
});
test('dateEquals', function () {
    $rule = Rule::make('date')->dateEquals('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('date_equals:2020-01-01');
});

