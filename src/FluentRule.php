<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Default\DefaultRule;
use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;

class FluentRule
{
    public static function string(string $attribute = ''): StringRuleBuilder
    {
        return new StringRuleBuilder($attribute);
    }

    public static function numeric(string $attribute = ''): NumericRuleBuilder
    {
        return new NumericRuleBuilder($attribute);
    }

    public static function date(string $attribute = ''): DateRuleBuilder
    {
        return new DateRuleBuilder($attribute);
    }

    public static function file(string $attribute = ''): FileRuleBuilder
    {
        return new FileRuleBuilder($attribute);
    }

    public static function array(string $attribute = ''): ArrayRuleBuilder
    {
        return new ArrayRuleBuilder($attribute);
    }

    // Reusable Individual Rules from DefaultRule:
    public static function name(string $attribute = 'name', bool $required = true, int $max = 255, bool|string $unique = false): Rule
    {
        return DefaultRule::name($attribute, $required, $max, $unique);
    }

    public static function email(string $attribute = 'email'): Rule
    {
        return DefaultRule::email($attribute);
    }

    public static function password(string $attribute = 'password'): Rule
    {
        return DefaultRule::password($attribute);
    }

    public static function registerPassword(): Rule
    {
        return DefaultRule::registerPassword();
    }

    public static function phoneNumber(string $attribute = 'phone_number'): Rule
    {
        return DefaultRule::phoneNumber($attribute);
    }

    public static function postalCode(string $attribute = 'postal_code'): Rule
    {
        return DefaultRule::postalCode($attribute);
    }

    public static function uuid(string $attribute = 'uuid'): Rule
    {
        return DefaultRule::uuid($attribute);
    }

    public static function ulid(string $attribute = 'ulid'): Rule
    {
        return DefaultRule::ulid($attribute);
    }

    public static function url(string $attribute = 'url', array $urls = ['http', 'https']): Rule
    {
        return DefaultRule::url($attribute, $urls);
    }

    public static function image(string $attribute = 'image'): Rule
    {
        return DefaultRule::image($attribute);
    }

    public static function text(string $attribute = 'text', int $max = 16000, bool $isRequired = true, ?string $stringMessage = null, ?string $maxMessage = null): Rule
    {
        return (new DefaultRule)->text($attribute, $max, $isRequired, $stringMessage, $maxMessage);
    }

    public static function longText(string $attribute = 'long_text', int $max = 65000, bool $isRequired = true, ?string $stringMessage = null, ?string $maxMessage = null): Rule
    {
        return (new DefaultRule)->longText($attribute, $max, $isRequired, $stringMessage, $maxMessage);
    }

    public static function images(string $attribute = 'images', ?callable $callback = null): ArrayRuleBuilder
    {
        $fileBuilder = new FileRuleBuilder;
        $fileBuilder->image();

        if ($callback) {
            $callback($fileBuilder);
        }

        return (new ArrayRuleBuilder($attribute))->each($fileBuilder);
    }

    public static function files(string $attribute = 'files', ?callable $callback = null): ArrayRuleBuilder
    {
        $fileBuilder = new FileRuleBuilder;

        if ($callback) {
            $callback($fileBuilder);
        }

        return (new ArrayRuleBuilder($attribute))->each($fileBuilder);
    }

    // Reusable Schemas from DefaultRuleSchema:
    public static function login(bool $remember = true): RuleSchema
    {
        return DefaultRuleSchema::login($remember);
    }

    public static function register(bool $passwordConfirmation = true): RuleSchema
    {
        return DefaultRuleSchema::register($passwordConfirmation);
    }

    public static function resetPassword(): RuleSchema
    {
        return DefaultRuleSchema::resetPassword();
    }

    public static function updatePassword(): RuleSchema
    {
        return DefaultRuleSchema::updatePassword();
    }

    public static function contact(): RuleSchema
    {
        return DefaultRuleSchema::contact();
    }

    public static function feedback(): RuleSchema
    {
        return DefaultRuleSchema::feedback();
    }
}
