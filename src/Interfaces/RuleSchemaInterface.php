<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

use Alpayklncrsln\RuleSchema\BaseRuleBuilder;
use Alpayklncrsln\RuleSchema\RuleSchema;

interface RuleSchemaInterface
{
    public static function create(BaseRuleBuilder|RuleSchema|array ...$rules): RuleSchema;

    public function getRules(): array;

    public function when(bool $condition, BaseRuleBuilder|RuleSchema|array ...$rules): RuleSchema;

    public function merge(BaseRuleBuilder|RuleSchema|array ...$rules): RuleSchema;

    public function existsMerge($attribute, BaseRuleBuilder|RuleSchema|array ...$rules): RuleSchema;

    public function ruleClass(string $attribute, mixed $rule): RuleSchema;
}
