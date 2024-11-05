<?php

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

test('create', function () {
    $rule = RuleSchema::create(
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    );

    expect($rule->getRules())->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});

test('merge', function () {
    $rule = RuleSchema::create()->merge(
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    )->getRules();

    expect($rule)->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});

test('when', function () {
    $rule = RuleSchema::create()->when(true,
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    )->getRules();

    expect($rule)->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});

test('existsMerge', function () {
    $rule = RuleSchema::create(
        Rule::make('name')->required(),
    )->existsMerge(
        'name',
        Rule::make('email')->email(true),
    )->getRules();

    expect($rule)->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});

test('auth', function () {
    \Illuminate\Support\Facades\Auth::shouldReceive('check')->once() ->andReturn(true);
    $rule = RuleSchema::create()->auth(
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    )->getRules();

    expect($rule)->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});
test('notAuth', function () {
    \Illuminate\Support\Facades\Auth::shouldReceive('check')->once() ->andReturn(false);

    $rule = RuleSchema::create()->notAuth(
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    )->getRules();

    expect($rule)->toBe([
        'name' => ['required'],
        'email' => ['email:dns'],
    ]);
});

test('model', function () {
//    $rule = RuleSchema::create()->model(User::class)->getRules();
//    expect($rule)->toBe([
//        'name' => ['required'],
//        'email' => ['email:dns'],
//    ]);
})->todo();

test('arraySchema', function () {
    $rule = RuleSchema::create()->arraySchema('images', Rule::make('name')->required())->getRules();
    expect($rule)->toBe([
        'images.name' => ['required'],
    ]);
});

test('bailed', function () {
    $rule = RuleSchema::create(
        Rule::make('name')->required(),
        Rule::make('email')->email(true),
    )->bailed()->getRules();

    expect($rule)->toBe([
        'name' => ['required', 'bail'],
        'email' => ['email:dns', 'bail'],
    ]);
});

test('ruleClass', function () {
//    $rule = RuleSchema::create()->ruleClass( 'name',\Illuminate\Validation\Rule::enum(\Alpayklncrsln\RuleSchema\Enums\FileMime::class))->getRules();
//    expect($rule)->toBe([
//        'name' => ['required'],
//    ]);
})->todo();
