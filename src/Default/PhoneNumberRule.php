<?php

namespace Alpayklncrsln\RuleSchema\Default;

use Alpayklncrsln\RuleSchema\Rule;

class PhoneNumberRule extends Rule
{
    public function general(): Rule
    {
        return $this->string()->regex('/^(\+?\d{1,3}[- ]?)?\d{10}$/')
            ->min(10)->max(15);
    }



}
