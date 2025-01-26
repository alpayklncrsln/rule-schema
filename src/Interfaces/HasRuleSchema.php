<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

use Alpayklncrsln\RuleSchema\RuleSchema;

interface HasRuleSchema
{
    public function ruleSchema(): RuleSchema;
}
