<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\HasRuleSchema;
use Illuminate\Foundation\Http\FormRequest;

class RuleSchemaRequest extends FormRequest implements HasRuleSchema
{
    public function rules(): array
    {
        return $this->ruleSchema()->getRules();
    }

    public function messages(): array
    {
        return $this->ruleSchema()->getMessages();
    }

    public function ruleSchema(): RuleSchema
    {
        return RuleSchema::create();
    }
}
