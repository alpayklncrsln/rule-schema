<?php

namespace Alpayklncrsln\RuleSchema\Enums;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Alpayklncrsln\RuleSchema\Traits\MimeTrait;

enum AudioMime: string implements MimeEnumInterface
{
    use MimeTrait;

    case AAC = 'aac';

    public function type(): string
    {
        return match ($this) {
            self::AAC => 'audio/aac',
        };
    }
}
