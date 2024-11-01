<?php

namespace Alpayklncrsln\RuleSchema\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alpayklncrsln\RuleSchema\RuleSchema
 */
class RuleSchema extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Alpayklncrsln\RuleSchema\RuleSchema::class;
    }
}
