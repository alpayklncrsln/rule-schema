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
