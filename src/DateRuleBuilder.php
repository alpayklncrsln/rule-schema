<?php

namespace Alpayklncrsln\RuleSchema;

class DateRuleBuilder extends BaseRuleBuilder
{
    public function __construct(string $attribute = '')
    {
        parent::__construct($attribute);
        $this->date();
    }

    public function date(bool $check = true, ?string $message = null): self
    {
        $this->rule['date'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function dateEquals(string $value, ?string $message = null): self
    {
        $this->date();
        $this->rule['date_equals'] = $value;
        $this->setMessage('date_equals', $message);

        return $this;
    }

    public function dateFormat(string $format, ?string $message = null): self
    {
        if (!$this->isRuleCheck('date')) {
            $this->date();
        }
        $this->rule['date_format'] = $format;
        $this->setMessage('date_format', $message);

        return $this;
    }

    public function dateTime(?string $message = null): self
    {
        $this->rule['datetime'] = true;
        $this->setMessage('datetime', $message);

        return $this;
    }

    public function after(string $after, ?string $message = null): self
    {
        $this->rule['after'] = $after;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function afterDate(string $value, ?string $message = null): self
    {
        $this->date();
        $this->after($value);
        $this->setMessage('after', $message);

        return $this;
    }

    public function afterDateYesterday(?string $message = null): self
    {
        $this->date();
        $this->after('yesterday');
        $this->setMessage('after', $message);

        return $this;
    }

    public function afterDateTomorrow(?string $message = null): self
    {
        $this->date();
        $this->after('tomorrow');
        $this->setMessage('after', $message);

        return $this;
    }

    public function before(string $before, ?string $message = null): self
    {
        $this->rule['before'] = $before;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function beforeDate(string $value, ?string $message = null): self
    {
        $this->date();
        $this->before($value);
        $this->setMessage('before', $message);

        return $this;
    }

    public function beforeDateYesterday(?string $message = null): self
    {
        $this->date();
        $this->before('yesterday');
        $this->setMessage('before', $message);

        return $this;
    }

    public function beforeDateTomorrow(?string $message = null): self
    {
        $this->date();
        $this->setMessage('before', $message);
        $this->before('tomorrow');

        return $this;
    }
}
