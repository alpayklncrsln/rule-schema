<?php

namespace Alpayklncrsln\RuleSchema\Default;

use Alpayklncrsln\RuleSchema\Rule;

class DefaultRule
{
    public static function name(string $attribute = 'name', bool $required = true, int $max = 255, bool|string $unique = false): Rule
    {
        $rule = Rule::make($attribute)->required($required)->string()->max($max);
        if ($unique) {
            $rule->unique($unique, $attribute);
        }

        return $rule;
    }

    public static function email(string $attribute = 'email'): Rule
    {
        return Rule::make($attribute)->required()->email();
    }

    public static function password(string $attribute = 'password'): Rule
    {
        return Rule::make($attribute)->required()->min(8)->max();
    }

    public static function registerPassword()
    {
        return Rule::make('password')->required()->min(8)->max()->confirmed();
    }

    public static function date(string $attribute = 'date'): Rule
    {
        return Rule::make($attribute)->required()->date();
    }

    public static function number(string $attribute = 'number'): Rule
    {
        return Rule::make($attribute)->numeric();
    }

    public static function string(string $attribute): Rule
    {
        return Rule::make($attribute)->string()->max()->required();
    }

    public static function image(string $attribute = 'image'): Rule
    {
        return Rule::make($attribute)->image()->size(2048);
    }

    public static function file(string $attribute = 'file'): Rule
    {
        return Rule::make($attribute)->file()->size(2048);
    }

    public static function url(string $attribute = 'url',$urls = ['http', 'https']): Rule
    {
        return Rule::make($attribute)->url($urls);
    }

    public static function uuid(string $attribute = 'uuid'): Rule
    {
        return Rule::make($attribute)->required()->string()->uuid();
    }

    public static function ulid(string $attribute = 'ulid'): Rule
    {
        return Rule::make($attribute)->ulid()->string()->required();
    }

    public static function phoneNumber(string $attribute = 'phone_number'): Rule
    {
        return Rule::make($attribute)->required()->numeric()
            ->regex('/^(\+?\d{1,3}[- ]?)?\d{10}$/')
            ->min(10)->max(15);
    }

    public static function postalCode(string $attribute = 'postal_code'): Rule
    {
        return Rule::make($attribute)->required()->regex('/^\d{5}(-\d{4})?$/');
    }

    public static function emailEndsWith(string $attribute = 'email', string ...$domain): Rule
    {
        return Rule::make('email')->required()->email()->endsWith($domain);
    }
}
