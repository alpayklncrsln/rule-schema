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

test('rule acceptedIf check', function () {
    $rule = Rule::make('name')->acceptedIf('field','value')
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
