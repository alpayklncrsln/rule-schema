<?php

namespace Alpayklncrsln\RuleSchema\Default;

use Alpayklncrsln\RuleSchema\Rule;

class DefaultRule
{
    public static function name(string $attribute = 'name'): Rule {
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


}
