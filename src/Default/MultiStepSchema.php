<?php

namespace Alpayklncrsln\RuleSchema\Default;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Support\Facades\Request;

class MultiStepSchema
{
    protected string $attribute = 'step';

    protected bool $allSteps = false;

    protected ?RuleSchema $ruleSchema;

    public function __construct(string $attribute = 'step', bool $allSteps = false, ?RuleSchema $ruleSchema = null)
    {
        $this->allSteps = $allSteps;
        $this->attribute = $attribute;
        if ($ruleSchema == null) {
            $this->ruleSchema = RuleSchema::create();
        } else {
            $this->ruleSchema = $ruleSchema;
        }
    }

    public static function make(string $attribute = 'step', bool $allSteps = false, ?RuleSchema $ruleSchema = null): self
    {
        return new self($attribute, $allSteps, $ruleSchema);
    }

    public function setAllSteps(bool $allSteps): self
    {
        $this->allSteps = $allSteps;

        return $this;
    }

    public function allSteps(): self
    {
        $this->allSteps = true;

        return $this;
    }

    public function step(int|string $step, array|RuleSchema|Rule ...$rules): self
    {
        if ($this->allSteps) {
            $this->ruleSchema->arraySchema($this->attribute.'_'.$step, $rules, false);
        } else {
            $this->ruleSchema->when(Request::input($this->attribute) == $step, $rules);
        }

        return $this;
    }

    public function getRules(): array
    {
        return $this->ruleSchema->getRules();
    }

    public function getMessages(): array
    {
        return $this->ruleSchema->getMessages();
    }

    public function getSchema(): RuleSchema
    {
        return $this->ruleSchema;
    }
}
