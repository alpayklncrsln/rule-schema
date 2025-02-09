<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

interface RuleSchemaInterface
{
    public static function create(Rule|RuleSchema|array ...$rulesWW): RuleSchema;

    public function getRules(): array;

    public function when(bool $condition, Rule|RuleSchema|array ...$rules): RuleSchema;

    public function merge(array $rules): RuleSchema;

    public function existsMerge($attribute, Rule|RuleSchema|array ...$rules): RuleSchema;
}
