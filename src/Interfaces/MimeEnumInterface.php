<?php

namespace Alpayklncrsln\RuleSchema\Interfaces;

interface MimeEnumInterface
{
    public function type(): string;

    /**
     * Get the value of the enum case
     */
    public function getValue(): string;
}
