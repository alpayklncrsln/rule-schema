<?php

namespace Alpayklncrsln\RuleSchema;

class ArrayRuleBuilder extends BaseRuleBuilder
{
    public ?BaseRuleBuilder $eachBuilder = null;

    public ?array $childBuilders = null;

    public function __construct(string $attribute = '')
    {
        parent::__construct($attribute);
        $this->array();
    }

    public function array(bool $check = true, ?string $message = null): self
    {
        $this->rule['array'] = $check;
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

    public function each(BaseRuleBuilder $builder): self
    {
        $this->eachBuilder = $builder;

        return $this;
    }

    public function children(array $builders): self
    {
        $this->childBuilders = $builders;

        return $this;
    }
}
