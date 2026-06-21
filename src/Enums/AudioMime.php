<?php

namespace Alpayklncrsln\RuleSchema\Enums;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Alpayklncrsln\RuleSchema\Traits\MimeTrait;

enum AudioMime: string implements MimeEnumInterface
{
    use MimeTrait;

    case AAC = 'aac';
    case MID = 'mid';
    case MIDI = 'midi';
    case MP3 = 'mp3';
    case OPUS = 'opus';
    case OGA = 'oga';
    case WAV = 'wav';
    case WEBA = 'weba';

    public function type(): string
    {
        return match ($this) {
            self::AAC => 'audio/aac',
            self::MID, self::MIDI => 'audio/midi',
            self::MP3 => 'audio/mpeg',
            self::OPUS, self::OGA => 'audio/ogg',
            self::WAV => 'audio/wav',
            self::WEBA => 'audio/webm',
        };
    }
}
