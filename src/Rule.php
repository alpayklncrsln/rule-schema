<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class Rule
{
    public string $attribute = '';

    protected array $rule = [];

    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public static function make(string $attribute): self
    {
        return new Rule($attribute);
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    private function isRuleCheck(string $ruleName): bool
    {
        return array_key_exists($ruleName, $this->rule);
    }

    public function ruleClass(ValidationRule $class): self
    {
        $this->rule[] = $class;

        return $this;
    }

    public function accepted(bool $check = true): self
    {
        $this->rule['accepted'] = $check;

        return $this;
    }

    public function acceptedIf(string $field, string $value): self
    {
        $this->rule['accepted_if'] = $field.','.$value;

        return $this;
    }

    public function after(string $after): self
    {
        $this->rule['after'] = $after;

        return $this;
    }

    public function afterDate(string $value): self
    {
        $this->date();
        $this->after($value);

        return $this;
    }

    public function afterDateYesterday(): self
    {
        $this->date();
        $this->after('yesterday');

        return $this;
    }

    public function afterDateTomorrow(): self
    {
        $this->date();
        $this->after('tomorrow');

        return $this;
    }

    public function alpha(string $value): self
    {
        $this->rule['alpha'] = $value;

        return $this;
    }

    public function alphaAscii(): self
    {
        $this->alpha('ascii');

        return $this;
    }

    public function alphaNumeric(string $value): self
    {
        $this->rule['alpha_num'] = $value;

        return $this;
    }

    public function alphaNumericAscii(): self
    {
        $this->alphaNumeric('ascii');

        return $this;
    }

    public function alphaDash(string $value): self
    {
        $this->rule['alpha_dash'] = $value;

        return $this;
    }

    public function before(string $before): self
    {
        $this->rule['before'] = $before;

        return $this;
    }

    public function beforeDate(string $value): self
    {
        $this->date();
        $this->before($value);

        return $this;
    }

    public function beforeDateYesterday(): self
    {
        $this->date();
        $this->before('yesterday');

        return $this;
    }

    public function beforeDateTomorrow(): self
    {
        $this->date();
        $this->before('tomorrow');

        return $this;
    }

    public function between(int $min, int $max): self
    {
        $this->rule['between'] = "$min,$max";

        return $this;
    }

    public function boolean(bool $check = true): self
    {
        $this->rule['boolean'] = $check;

        return $this;
    }

    public function confirmed(bool $check = true): self
    {
        $this->rule['confirmed'] = $check;

        return $this;
    }

    public function contains(string ...$contains): self
    {
        $this->rule['contains'] = implode(',', $contains);

        return $this;
    }

    public function currentPassword(string $guard): self
    {
        $this->rule['current_password'] = $guard;

        return $this;
    }

    public function date(bool $check = true): self
    {
        $this->rule['date'] = $check;

        return $this;
    }

    public function dateEquals(string $value): self
    {
        $this->date();
        $this->rule['date_equals'] = $value;

        return $this;
    }

    public function dateFormat(string $format): self
    {
        if (! $this->isRuleCheck('date')) {
            $this->date();
        }
        $this->rule['date_format'] = $format;

        return $this;
    }

    public function dateTime(): self
    {
        $this->rule['datetime'] = true;

        return $this;
    }

    public function decimal(int $min, ?int $max = null): self
    {
        $this->rule['decimal'] = "$min".($max ? ",$max" : '');

        return $this;
    }

    public function declined(bool $check = true): self
    {
        $this->rule[__FUNCTION__] = $check;

        return $this;
    }

    public function declinedIf(string $field, bool $value): self
    {
        $this->rule['declined_if'] = $field.','.$value;

        return $this;
    }

    public function different(string $field): self
    {
        $this->rule['different'] = $field;

        return $this;
    }

    public function digits(int $digits): self
    {
        $this->rule['digits'] = $digits;

        return $this;
    }

    public function digitsBetween(int $min, int $max): self
    {
        $this->rule['digits_between'] = "$min,$max";

        return $this;
    }

    public function demensions(string $value): self
    {
        $this->rule['dimensions'] = $value;

        return $this;
    }

    public function demensionsImageWidthHeight(int $width, int $height): self
    {
        $this->image();
        $this->demensions('width:'.$width.',height:'.$height);

        return $this;
    }

    public function demensionsImageMinWidthMinHeight(int $minWidth, int $minHeight): self
    {
        $this->image();
        $this->demensions('min_width:'.$minWidth.',min_height:'.$minHeight);

        return $this;
    }

    public function distinct(bool|string $check = true): self
    {
        $this->rule['distinct'] = $check;

        return $this;
    }

    public function distinctIgnoreCase(): self
    {
        $this->distinct('ignore_case');

        return $this;
    }

    public function doesntStartWith(string ...$starts): self
    {
        $this->rule['doesnt_starts_with'] = implode(',', $starts);

        return $this;
    }

    public function doesntEndWith(string ...$ends): self
    {
        $this->rule['doesnt_ends_with'] = implode(',', $ends);

        return $this;
    }

    public function email(bool $dnsCheck = false, bool $rfcCheck = false, bool $spoofCheck = false, bool $strictCheck = false,
        ?string $extra = null): self
    {
        $this->rule['email'] = ($dnsCheck || $rfcCheck || $spoofCheck ? '' : true).
            ($dnsCheck ? 'dns' : '').($rfcCheck ? 'rfc' : '').($spoofCheck ? 'spoof' : '').
            ($extra ? ','.$extra : '').($strictCheck ? 'strict' : '');

        return $this;
    }

    public function endsWith(string ...$ends): self
    {
        $this->rule['ends_with'] = implode(',', $ends);

        return $this;
    }

    public function exists(Model|string $table, ?string $column = null): self
    {
        $this->rule['exists'] = $table.(! is_null($column) ? ','.$column : '');

        return $this;
    }

    public function hexColor(bool $check = true): self
    {
        $this->rule['hex_color'] = $check;

        return $this;
    }

    public function string(bool $check = true): self
    {
        $this->rule['string'] = $check;

        return $this;
    }

    public function max(int $max = 255): self
    {
        $this->rule['max'] = $max;

        return $this;
    }

    public function min(int $min = 1): self
    {
        $this->rule['min'] = $min;

        return $this;
    }

    public function numeric(bool $check = true): self
    {
        $this->rule['numeric'] = $check;

        return $this;
    }

    public function array(bool $check = true): self
    {
        $this->rule['array'] = $check;

        return $this;
    }

    public function in(array $in): self
    {
        $this->rule['in'] = implode(',', $in);

        return $this;
    }

    public function unique(Model|string $table, string $column = 'id'): self
    {
        $this->rule['unique'] = "$table,$column";

        return $this;
    }

    public function nullable(bool $check = true): self
    {
        $this->rule['nullable'] = $check;

        return $this;
    }

    public function image(): self
    {
        $this->rule['image'] = true;

        return $this;
    }

    public function file(): self
    {
        $this->rule['file'] = true;

        return $this;
    }

    public function activeUrl(): self
    {
        $this->rule['active_url'] = true;

        return $this;
    }

    public function regex(string $pattern): self
    {
        $this->rule['regex'] = $pattern;

        return $this;
    }

    public function notRegex(string $pattern): self
    {
        $this->rule['not_regex'] = $pattern;

        return $this;
    }

    public function gt(string $gt): self
    {
        $this->rule['gt'] = $gt;

        return $this;
    }

    public function lt(string $lt): self
    {
        $this->rule['lt'] = $lt;

        return $this;
    }

    public function gte(string $gte): self
    {
        $this->rule['gte'] = $gte;

        return $this;
    }

    public function lte(string $lte): self
    {
        $this->rule['lte'] = $lte;

        return $this;
    }

    public function json(bool $check = true): self
    {
        $this->rule['json'] = $check;

        return $this;
    }

    public function ip(bool $check = true): self
    {
        $this->rule['ip'] = $check;

        return $this;
    }

    public function macAddress(bool $check = true): self
    {
        $this->rule['mac_address'] = $check;

        return $this;
    }

    public function ipv4(bool $check = true): self
    {
        $this->rule['ipv4'] = $check;

        return $this;
    }

    public function ipv6(bool $check = true): self
    {
        $this->rule['ipv6'] = $check;

        return $this;
    }

    public function mimes(MimeEnumInterface|string ...$mimes): self
    {
        $this->rule['mimes'] = '';
        foreach ($mimes as $mime) {
            if (is_string($mime)) {
                $this->rule['mimes'] .= ','.$mime;
            } else {
                $this->rule['mimes'] .= ','.$mime->value;
            }
        }

        return $this;
    }

    public function mimetypes(string|MimeEnumInterface ...$mimetypes): self
    {
        $this->rule['mimetypes'] = '';
        foreach ($mimetypes as $mimeType) {
            if (is_string($mimeType)) {
                $this->rule['mimetypes'] .= ','.$mimeType;
            } else {
                $this->rule['mimetypes'] .= ','.$mimeType->value;
            }
        }

        return $this;
    }

    public function enum($enum): self
    {
        $this->rule['enum'] = implode(',', $enum::cases());

        return $this;
    }

    public function mimeAndMimetypes(MimeEnumInterface ...$mimes): self
    {
        $_mimes = [];
        $_mimeTypes = [];
        foreach ($mimes as $mime) {
            $_mimes[] = $mime->value;
            $_mimeTypes[] = $mime->type();
        }
        $this->mimes(...$_mimes);
        $this->mimetypes(...$_mimeTypes);

        return $this;
    }

    public function maxDigits(int $digits): self
    {
        $this->rule['max_digits'] = $digits;

        return $this;
    }

    public function minDigits(int $digits): self
    {
        $this->rule['min_digits'] = $digits;

        return $this;
    }

    public function multipleOf(string $value): self
    {
        $this->rule['multiple_of'] = $value;

        return $this;
    }

    public function missing(bool $check = true): self
    {
        $this->rule['missing'] = $check;

        return $this;
    }

    public function missingIf(string $field, string $value): self
    {
        $this->rule['missing_if'] = $field.','.$value;

        return $this;
    }

    public function missingUnless(string $field, string $value): self
    {
        $this->rule['missing_unless'] = $field.','.$value;

        return $this;
    }

    public function missingWith(string ...$values): self
    {
        $this->rule['missing_with'] = implode(',', $values);

        return $this;
    }

    public function notIn(string ...$values): self
    {
        $this->rule['not_in'] = implode(',', $values);

        return $this;
    }

    public function present(bool $check = true): self
    {
        $this->rule['present'] = $check;

        return $this;
    }

    public function presentIf(string $field, string ...$value): self
    {
        $this->rule['present_if'] = $field.','.implode(',', $value);

        return $this;
    }

    public function presentUnless(string $field, string ...$value): self
    {
        $this->rule['present_unless'] = $field.','.implode(',', $value);

        return $this;
    }

    public function presentWith(string ...$value): self
    {
        $this->rule['present_with'] = implode(',', $value);

        return $this;
    }

    public function presentWithAll(string ...$value): self
    {
        $this->rule['present_with_all'] = implode(',', $value);

        return $this;
    }

    public function prohibited(bool $check = true): self
    {
        $this->rule['prohibited'] = $check;

        return $this;
    }

    public function prohibitedIf(string $field, string ...$value): self
    {
        $this->rule['prohibited_if'] = $field.','.implode(',', $value);

        return $this;
    }

    public function prohibitedUnless(string $field, string ...$value): self
    {
        $this->rule['prohibited_unless'] = $field.','.implode(',', $value);

        return $this;
    }

    public function prohibits(string ...$field): self
    {
        $this->rule['prohibits'] = implode(',', $field);

        return $this;
    }

    public function required(bool $check = true): self
    {
        $this->rule['required'] = $check;

        return $this;
    }

    public function requiredIf(string $field, string ...$value): self
    {
        $this->rule['required_if'] = $field.','.implode(',', $value);

        return $this;
    }

    public function requiredIfAccepted(string ...$field): self
    {
        $this->rule['required_if_accepted'] = implode(',', $field);

        return $this;
    }

    public function requiredIfDeclined(string ...$field): self
    {
        $this->rule['required_if_declined'] = implode(',', $field);

        return $this;
    }

    public function requiredWith(string ...$field): self
    {
        $this->rule['required_with'] = implode(',', $field);

        return $this;
    }

    public function requiredWithAll(string ...$field): self
    {
        $this->rule['required_with_all'] = implode(',', $field);

        return $this;
    }

    public function requiredWithout(string ...$field): self
    {
        $this->rule['required_without'] = implode(',', $field);

        return $this;
    }

    public function requiredWithoutAll(string ...$field): self
    {
        $this->rule['required_without_all'] = implode(',', $field);

        return $this;
    }

    public function requiredUnless(string $field, string ...$value): self
    {
        $this->rule['required_unless'] = $field.','.implode(',', $value);

        return $this;
    }

    public function requiredArrayKeys(string ...$key): self
    {
        $this->rule['required_array_keys'] = implode(',', $key);

        return $this;
    }

    public function same(string $field): self
    {
        $this->rule['same'] = $field;

        return $this;
    }

    public function size(string $size): self
    {
        $this->rule['size'] = $size;

        return $this;
    }

    public function startsWith(string ...$value): self
    {
        $this->rule['starts_with'] = implode(',', $value);

        return $this;
    }

    public function timezone(string $timezone): self
    {
        $this->rule['timezone'] = $timezone;

        return $this;
    }

    public function uppercase(bool $check = true): self
    {
        $this->rule['uppercase'] = $check;

        return $this;
    }

    public function url(string ...$value): self
    {
        $this->rule['url'] = implode(',', $value);

        return $this;
    }

    public function urlHttp(): self
    {
        $this->url('http');

        return $this;
    }

    public function urlHttps(): self
    {
        $this->url('https');

        return $this;
    }

    public function urlHttpAndHttps(): self
    {
        $this->url('http', 'https');

        return $this;
    }

    public function ulid(bool $check = true): self
    {
        $this->rule['ulid'] = $check;

        return $this;
    }

    public function uuid(bool $check = true): self
    {
        $this->rule['uuid'] = $check;

        return $this;
    }

    public function getRule(): array
    {
        $ruleData = [];
        foreach ($this->rule as $key => $value) {
            if (is_bool($value)) {
                $ruleData[] = $key;
            } else {
                $ruleData[] = "$key:$value";
            }
        }

        return [$this->attribute => $ruleData];

    }
}
