<?php

namespace Alpayklncrsln\RuleSchema;

class NumericRuleBuilder extends BaseRuleBuilder
{
    public function __construct(string $attribute = '')
    {
        parent::__construct($attribute);
        $this->numeric();
    }

    public function numeric(bool $check = true, ?string $message = null): self
    {
        $this->rule['numeric'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function integer(bool $check = true, ?string $message = null): self
    {
        $this->rule['integer'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function max(int $max, ?string $message = null): self
    {
        $this->rule['max'] = $max;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function min(int $min, ?string $message = null): self
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

    public function digits(int $digits, ?string $message = null): self
    {
        $this->rule['digits'] = $digits;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function digitsBetween(int $min, int $max, ?string $message = null): self
    {
        $this->rule['digits_between'] = "$min,$max";
        $this->setMessage('digits_between', $message);

        return $this;
    }

    public function maxDigits(int $digits, ?string $message = null): self
    {
        $this->rule['max_digits'] = $digits;
        $this->setMessage('max_digits', $message);

        return $this;
    }

    public function minDigits(int $digits, ?string $message = null): self
    {
        $this->rule['min_digits'] = $digits;
        $this->setMessage('min_digits', $message);

        return $this;
    }

    public function decimal(int $min, ?int $max = null, ?string $message = null): self
    {
        $this->rule['decimal'] = "$min" . ($max ? ",$max" : '');
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function multipleOf(string $value, ?string $message = null): self
    {
        $this->rule['multiple_of'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function gt(string $gt, ?string $message = null): self
    {
        $this->rule['gt'] = $gt;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function lt(string $lt, ?string $message = null): self
    {
        $this->rule['lt'] = $lt;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function gte(string $gte, ?string $message = null): self
    {
        $this->rule['gte'] = $gte;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function lte(string $lte, ?string $message = null): self
    {
        $this->rule['lte'] = $lte;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }
}
