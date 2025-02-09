<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

interface RuleSchemaInterface
{
    public static function create(Rule|array ...$rulesWW): RuleSchema;

    public function getRules(): array;

    public function when(bool $condition, Rule|array ...$rules): RuleSchema;

    public function merge(array $rules): RuleSchema;

    public function existsMerge($attribute, Rule|array ...$rules): RuleSchema;
}
