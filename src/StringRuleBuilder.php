<?php

namespace Alpayklncrsln\RuleSchema;

use Illuminate\Validation\Rules\Password;

class StringRuleBuilder extends BaseRuleBuilder
{
    public function __construct(string $attribute = '')
    {
        parent::__construct($attribute);
        $this->string();
    }

    public function alpha(string $value, ?string $message = null): self
    {
        $this->rule['alpha'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function alphaAscii(?string $message = null): self
    {
        $this->alpha('ascii', $message);

        return $this;
    }

    public function alphaNumeric(string $value, ?string $message = null): self
    {
        $this->rule['alpha_num'] = $value;
        $this->setMessage('alpha_num', $message);

        return $this;
    }

    public function alphaNumericAscii(?string $message = null): self
    {
        $this->alphaNumeric('ascii', $message);

        return $this;
    }

    public function alphaDash(string $value, ?string $message = null): self
    {
        $this->rule['alpha_dash'] = $value;
        $this->setMessage('alpha_dash', $message);

        return $this;
    }

    public function contains(array $contains, ?string $message = null): self
    {
        $this->rule['contains'] = $contains;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function doesntStartWith(array $starts, ?string $message = null): self
    {
        $this->rule['doesnt_starts_with'] = $starts;
        $this->setMessage('doesnt_starts_with', $message);

        return $this;
    }

    public function doesntEndWith(array $ends, ?string $message = null): self
    {
        $this->rule['doesnt_ends_with'] = $ends;
        $this->setMessage('doesnt_ends_with', $message);

        return $this;
    }

    public function email(bool        $dnsCheck = false, bool $rfcCheck = false, bool $spoofCheck = false, bool $strictCheck = false,
                          bool|string $extra = false, ?string $message = null): self
    {
        if ($dnsCheck || $rfcCheck || $spoofCheck || $strictCheck || $extra) {
            $this->rule['email'] = ($dnsCheck ? 'dns' : null) . ($rfcCheck ? 'rfc' : null) . ($spoofCheck ? 'spoof' : null) .
                ($extra ? ',' . $extra : null) . ($strictCheck ? 'strict' : null);
        } else {
            $this->rule['email'] = true;
        }
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function endsWith(array $ends, ?string $message = null): self
    {
        $this->rule['ends_with'] = $ends;
        $this->setMessage('ends_with', $message);

        return $this;
    }

    public function hexColor(bool $check = true, ?string $message = null): self
    {
        $this->rule['hex_color'] = $check;
        $this->setMessage('hex_color', $message);

        return $this;
    }

    public function string(bool $check = true, ?string $message = null): self
    {
        $this->rule['string'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function max(int $max = 255, ?string $message = null): self
    {
        $this->rule['max'] = $max;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function min(int $min = 1, ?string $message = null): self
    {
        $this->rule['min'] = $min;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function between(int $min, int $max, ?string $message = null): self
    {
        $this->rule['between'] = "$min,$max";
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function size(int $size, ?string $message = null): self
    {
        $this->rule['size'] = $size;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function regex(string $pattern, ?string $message = null): self
    {
        $this->rule['regex'] = $pattern;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function notRegex(string $pattern, ?string $message = null): self
    {
        $this->rule['not_regex'] = $pattern;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function uppercase(bool $check = true, ?string $message = null): self
    {
        $this->rule['uppercase'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function lowercase(bool $check = true, ?string $message = null): self
    {
        $this->rule['lowercase'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function url(array $value, ?string $message = null): self
    {
        $this->rule['url'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function urlHttp(?string $message = null): self
    {
        $this->url(['http'], $message);

        return $this;
    }

    public function urlHttps(?string $message = null): self
    {
        $this->url(['https'], $message);

        return $this;
    }

    public function urlHttpAndHttps(?string $message = null): self
    {
        $this->url(['http', 'https'], $message);

        return $this;
    }

    public function ulid(bool $check = true, ?string $message = null): self
    {
        $this->rule['ulid'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function uuid(bool $check = true, ?string $message = null): self
    {
        $this->rule['uuid'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function timezone(string $timezone = 'all', ?string $message = null): self
    {
        $this->rule['timezone'] = $timezone;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function activeUrl(bool $check = true, ?string $message = null): self
    {
        $this->rule['active_url'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function currentPassword(bool|string $guard = true, ?string $message = null): self
    {
        $this->rule['current_password'] = $guard;
        $this->setMessage('current_password', $message);

        return $this;
    }

    public function startsWith(array $value, ?string $message = null): self
    {
        $this->rule['starts_with'] = $value;
        $this->setMessage('starts_with', $message);

        return $this;
    }

    public function alphaDashOrSpace(?string $message = null): self
    {
        return $this->regex('/^[\pL\pM\pN_-]+(?:\s+[\pL\pM\pN_-]+)*$/u', $message);
    }

    public function noHtml(?string $message = null): self
    {
        return $this->notRegex('/<[^>]*>/', $message);
    }

    public function passwordSecurity(
        int     $min = 8,
        bool    $mixedCase = false,
        bool    $letters = false,
        bool    $numbers = false,
        bool    $symbols = false,
        bool    $uncompromised = false,
        ?string $message = null
    ): self
    {
        $passwordRule = Password::min($min);

        if ($mixedCase) {
            $passwordRule->mixedCase();
        }
        if ($letters) {
            $passwordRule->letters();
        }
        if ($numbers) {
            $passwordRule->numbers();
        }
        if ($symbols) {
            $passwordRule->symbols();
        }
        if ($uncompromised) {
            $passwordRule->uncompromised();
        }

        $this->rule['password_security'] = $passwordRule;
        $this->setMessage('password_security', $message);

        return $this;
    }
}
