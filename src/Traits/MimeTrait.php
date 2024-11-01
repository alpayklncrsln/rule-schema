<?php

namespace Alpayklncrsln\RuleSchema\Traits;

trait MimeTrait
{
    public static function toArray(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_column(self::cases(), 'label');
    }
}
