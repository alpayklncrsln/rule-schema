<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class Rule
{
    public string $attribute = '';

    protected array $rule = [];

    protected array $messages = [];

    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public static function make(string $attribute): self
    {
        return new Rule($attribute);
    }

    protected function setMessage(string $ruleName, string $message = null): void
    {
        if (!is_null($message)) {
            $this->messages[$ruleName] = $message;
        }
    }

    public function getMessage(): array
    {
        $message = [];
        foreach ($this->messages as $key => $value) {
            $message[$this->attribute . '.' . $key] = $value;
        }

        return $message;
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

    public function accepted(bool $check = true, string $message = null): self
    {
        $this->rule['accepted'] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function acceptedIf(string $field, string $value, string $message = null): self
    {
        $this->rule['accepted_if'] = $field . ',' . $value;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function after(string $after, string $message = null): self
    {
        $this->rule['after'] = $after;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function afterDate(string $value, string $message = null): self
    {
        $this->date();
        $this->after($value);
        $this->setMessage('after', $message);
        return $this;
    }

    public function afterDateYesterday(string $message = null): self
    {
        $this->date();
        $this->after('yesterday');
        $this->setMessage('after', $message);
        return $this;
    }

    public function afterDateTomorrow(string $message = null): self
    {
        $this->date();
        $this->after('tomorrow');
        $this->setMessage('after', $message);

        return $this;
    }

    public function alpha(string $value, string $message = null): self
    {
        $this->rule['alpha'] = $value;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function alphaAscii(string $message = null): self
    {
        $this->alpha('ascii');
        $this->setMessage('alpha', $message);
        return $this;
    }

    public function alphaNumeric(string $value, string $message = null): self
    {
        $this->rule['alpha_num'] = $value;
        $this->setMessage('alpha_num', $message);

        return $this;
    }

    public function alphaNumericAscii(string $message = null): self
    {
        $this->alphaNumeric('ascii');
        $this->setMessage('alpha_num', $message);

        return $this;
    }

    public function alphaDash(string $value, string $message = null): self
    {
        $this->rule['alpha_dash'] = $value;
        $this->setMessage('alpha_dash', $message);

        return $this;
    }

    public function bail(bool $check = true, string $message = null): self
    {
        $this->rule['bail'] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function before(string $before, string $message = null): self
    {
        $this->rule['before'] = $before;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function beforeDate(string $value, string $message = null): self
    {
        $this->date();
        $this->before($value);
        $this->setMessage('before', $message);
        return $this;
    }

    public function beforeDateYesterday(string $message = null): self
    {
        $this->date();
        $this->before('yesterday');
        $this->setMessage('before', $message);

        return $this;
    }

    public function beforeDateTomorrow(string $message = null): self
    {
        $this->date();
        $this->setMessage('before', $message);
        $this->before('tomorrow');

        return $this;
    }

    public function between(int $min, int $max, string $message = null): self
    {
        $this->rule['between'] = "$min,$max";
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function boolean(bool $check = true, string $message = null): self
    {
        $this->rule['boolean'] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function confirmed(bool $check = true, string $message = null): self
    {
        $this->rule['confirmed'] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function contains(array $contains, string $message = null): self
    {
        $this->rule['contains'] = $contains;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function currentPassword(bool|string $guard = true, string $message = null): self
    {
        $this->rule['current_password'] = $guard;
        $this->setMessage('current_password', $message);
        return $this;
    }

    public function date(bool $check = true, string $message = null): self
    {
        $this->rule['date'] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function dateEquals(string $value, string $message = null): self
    {
        $this->date();
        $this->rule['date_equals'] = $value;
        $this->setMessage('date_equals', $message);

        return $this;
    }

    public function dateFormat(string $format, string $message = null): self
    {
        if (!$this->isRuleCheck('date')) {
            $this->date();
        }
        $this->rule['date_format'] = $format;
        $this->setMessage('date_format', $message);

        return $this;
    }

    public function dateTime(string $message = null): self
    {
        $this->rule['datetime'] = true;
        $this->setMessage('datetime', $message);
        return $this;
    }

    public function decimal(int $min = 0, ?int $max = 2, string $message = null): self
    {
        $this->rule['decimal'] = "$min" . ($max ? ",$max" : '');
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function declined(bool $check = true, string $message = null): self
    {
        $this->rule[__FUNCTION__] = $check;
        $this->setMessage(__FUNCTION__, $message);
        return $this;
    }

    public function declinedIf(string $field, string $value, string $message = null): self
    {
        $this->rule['declined_if'] = $field . ',' . $value;
        $this->setMessage('declined_if', $message);
        return $this;
    }

    public function different(string $field, string $message = null): self
    {
        $this->rule['different'] = $field;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function digits(int $digits, string $message = null): self
    {
        $this->rule['digits'] = $digits;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function digitsBetween(int $min, int $max, string $message = null): self
    {
        $this->rule['digits_between'] = "$min,$max";
        $this->setMessage('digits_between', $message);

        return $this;
    }

    public function demensions(string $value, string $message = null): self
    {
        $this->rule['dimensions'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function demensionsImageWidthHeight(int $width, int $height, string $message = null): self
    {
        $this->image();
        $this->demensions('width:' . $width . ',height:' . $height);
        $this->setMessage('demensions', $message);

        return $this;
    }

    public function demensionsImageMinWidthMinHeight(int $minWidth, int $minHeight, string $message = null): self
    {
        $this->image();
        $this->demensions('min_width:' . $minWidth . ',min_height:' . $minHeight);
        $this->setMessage('demensions', $message);

        return $this;
    }

    public function distinct(bool|string $check = true, string $message = null): self
    {
        $this->rule['distinct'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function distinctIgnoreCase(string $message = null): self
    {
        $this->distinct('ignore_case');
        $this->setMessage('distinct', $message);

        return $this;
    }

    public function doesntStartWith(array $starts, string $message = null): self
    {
        $this->rule['doesnt_starts_with'] = $starts;
        $this->setMessage('doesnt_starts_with', $message);

        return $this;
    }

    public function doesntEndWith(array $ends, string $message = null): self
    {
        $this->rule['doesnt_ends_with'] = $ends;
        $this->setMessage('doesnt_ends_with', $message);

        return $this;
    }

    public function email(bool $dnsCheck = false, bool $rfcCheck = false, bool $spoofCheck = false, bool $strictCheck = false,
                          bool|string $extra = false, string $message = null): self
    {
        if ($dnsCheck || $rfcCheck || $spoofCheck || $strictCheck || $extra) {
            $this->rule['email'] = ($dnsCheck ? 'dns' : null) . ($rfcCheck ? 'rfc' : null) . ($spoofCheck ? 'spoof' : null) .
                ($extra ? ',' . $extra : null) . ($strictCheck ? 'strict' : null);
        } else {
            $this->rule['email'] = true;
        }
        $this->setMessage(__FUNCTION__,$message);

        return $this;
    }

    public function endsWith(array $ends, string $message = null): self
    {
        $this->rule['ends_with'] = $ends;
        $this->setMessage('ends_with', $message);

        return $this;
    }

    public function exists(Model|string $table, ?string $column = null, string $message = null): self
    {
        $this->rule['exists'] = $table . (!is_null($column) ? ',' . $column : '');
        $this->setMessage('exists', $message);

        return $this;
    }

    public function hexColor(bool $check = true, string $message = null): self
    {
        $this->rule['hex_color'] = $check;
        $this->setMessage('hex_color', $message);

        return $this;
    }

    public function string(bool $check = true, string $message = null): self
    {
        $this->rule['string'] = $check;
        $this->setMessage(__FUNCTION__,$message);

        return $this;
    }

    public function max(int $max = 255, string $message = null): self
    {
        $this->rule['max'] = $max;
        $this->setMessage(__FUNCTION__,$message);

        return $this;
    }

    public function min(int $min = 1, string $message = null): self
    {
        $this->rule['min'] = $min;
        $this->setMessage(__FUNCTION__,$message);

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
        $this->rule['in'] = $in;

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
        $this->rule['mimes'] = implode(',', array_map(
            fn($mime) => is_string($mime) ? $mime : $mime->getValue(),
            $mimes
        ));

        return $this;
    }

    public function mimetypes(string|MimeEnumInterface ...$mimetypes): self
    {
        $this->rule['mimetypes'] = implode(',', array_map(
            fn($mimeType) => is_string($mimeType) ? $mimeType : $mimeType->getValue(),
            $mimetypes
        ));

        return $this;
    }

    public function enum($enum): self
    {
        $this->rule['enum'] = $enum;

        return $this;
    }

    public function mimeAndMimetypes(MimeEnumInterface ...$mimes): self
    {
        $this->mimes(...array_map(fn($mime) => $mime->getValue(), $mimes));
        $this->mimetypes(...array_map(fn($mime) => $mime->type(), $mimes));

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
        $this->rule['missing_if'] = $field . ',' . $value;

        return $this;
    }

    public function missingUnless(string $field, string $value): self
    {
        $this->rule['missing_unless'] = $field . ',' . $value;

        return $this;
    }

    public function missingWith(string ...$values): self
    {
        $this->rule['missing_with'] = $values;

        return $this;
    }

    public function notIn(string ...$values): self
    {
        $this->rule['not_in'] = $values;

        return $this;
    }

    public function present(bool $check = true): self
    {
        $this->rule['present'] = $check;

        return $this;
    }

    public function presentIf(string $field, string ...$value): self
    {
        $this->rule['present_if'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function presentUnless(string $field, string ...$value): self
    {
        $this->rule['present_unless'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function presentWith(string ...$value): self
    {
        $this->rule['present_with'] = $value;

        return $this;
    }

    public function presentWithAll(string ...$value): self
    {
        $this->rule['present_with_all'] = $value;

        return $this;
    }

    public function prohibited(bool $check = true): self
    {
        $this->rule['prohibited'] = $check;

        return $this;
    }

    public function prohibitedIf(string $field, string ...$value): self
    {
        $this->rule['prohibited_if'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function prohibitedUnless(string $field, string ...$value): self
    {
        $this->rule['prohibited_unless'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function prohibits(string ...$field): self
    {
        $this->rule['prohibits'] = $field;

        return $this;
    }

    public function required(bool $check = true): self
    {
        $this->rule['required'] = $check;

        return $this;
    }

    public function requiredIf(string $field, string ...$value): self
    {
        $this->rule['required_if'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function requiredIfAccepted(string ...$field): self
    {
        $this->rule['required_if_accepted'] = $field;

        return $this;
    }

    public function requiredIfDeclined(string ...$field): self
    {
        $this->rule['required_if_declined'] = $field;

        return $this;
    }

    public function requiredWith(string ...$field): self
    {
        $this->rule['required_with'] = $field;

        return $this;
    }

    public function requiredWithAll(string ...$field): self
    {
        $this->rule['required_with_all'] = $field;

        return $this;
    }

    public function requiredWithout(string ...$field): self
    {
        $this->rule['required_without'] = $field;

        return $this;
    }

    public function requiredWithoutAll(string ...$field): self
    {
        $this->rule['required_without_all'] = $field;

        return $this;
    }

    public function requiredUnless(string $field, string ...$value): self
    {
        $this->rule['required_unless'] = $field . ',' . implode(',', $value);

        return $this;
    }

    public function requiredArrayKeys(string ...$key): self
    {
        $this->rule['required_array_keys'] = $key;

        return $this;
    }

    public function same(string $field): self
    {
        $this->rule['same'] = $field;

        return $this;
    }

    public function sometimes(): self
    {
        $this->rule['sometimes'] = true;

        return $this;
    }

    public function size(int $size): self
    {
        $this->rule['size'] = $size;

        return $this;
    }

    public function startsWith(string ...$value): self
    {
        $this->rule['starts_with'] = $value;

        return $this;
    }

    public function timezone(string $timezone = 'all'): self
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
        $this->rule['url'] = $value;

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

            $ruleData[] = match (true) {
                is_bool($value) => $value ? $key : null,
                is_array($value) => "$key:" . implode(',', $value),
                $key == 'enum' => "$key:" . implode(',', array_column($value::cases(), 'value')),
                is_string($value) || is_int($value) => "$key:$value",
                default => throw new \Exception(" Rule error:$key ")
            };
        }
        return [$this->attribute => array_filter($ruleData)];

    }
}
