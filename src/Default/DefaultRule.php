<?php

namespace Alpayklncrsln\RuleSchema\Default;

use Alpayklncrsln\RuleSchema\Rule;

class DefaultRule
{
    public static function name(string $attribute = 'name'): Rule
    {
        return Rule::make($attribute)->required()->max();
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

    public static function url(string $attribute = 'url'): Rule
    {
        return Rule::make($attribute)->url();
    }

    public static function uuid(string $attribute = 'uuid'): Rule
    {
        return Rule::make($attribute)->required()->string()->uuid();
    }

    public static function ulid(string $attribute = 'ulid'): Rule
    {
        return Rule::make($attribute)->ulid()->string()->required();
    }
}
