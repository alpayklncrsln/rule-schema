<?php

namespace Alpayklncrsln\RuleSchema\Traits;

use Alpayklncrsln\RuleSchema\RuleSchema;
use Exception;

trait InteractsWithRuleSchema
{
    protected function getRuleSchema(): RuleSchema
    {
        if (method_exists($this, 'ruleSchema')) {
            return $this->ruleSchema();
        }

        throw new Exception('Please define the ruleSchema() method returning a RuleSchema instance on your component.');
    }

    public function rules(): array
    {
        return $this->getRuleSchema()->getRules();
    }

    public function messages(): array
    {
        return $this->getRuleSchema()->getMessages();
    }
}
