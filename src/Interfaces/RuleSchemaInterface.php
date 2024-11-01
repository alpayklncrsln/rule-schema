<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;

interface RuleSchemaInterface
{
    public static function create(): RuleSchema;

    public function getRules(): array;

    public function when(bool $condition, Rule ...$rules): RuleSchema;

    public function merge(Rule ...$rules): RuleSchema;

    public function existsMerge($attribute, Rule ...$rules): RuleSchema;
}
