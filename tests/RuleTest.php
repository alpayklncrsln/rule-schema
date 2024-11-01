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

test('is rule check', function () {
    $rule = Rule::make('name')->required()
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->each
        ->toBeString();
});
