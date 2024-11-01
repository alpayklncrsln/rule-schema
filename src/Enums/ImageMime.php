<?php

namespace Alpayklncrsln\RuleSchema\Enums;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Alpayklncrsln\RuleSchema\Traits\MimeTrait;

enum ImageMime: string implements MimeEnumInterface
{
    use MimeTrait;

    case PNG = 'png';

    case JPG = 'jpg';

    case JPEG = 'jpeg';

    case GIF = 'gif';

    case SVG = 'svg';

    case WEBP = 'webp';

    case AVIF = 'avif';

    public function type(): string
    {
        return match ($this) {
            self::PNG => 'image/png',
            self::JPG => 'image/jpg',
            self::JPEG => 'image/jpeg',
            self::GIF => 'image/gif',
            self::SVG => 'image/svg+xml',
            self::WEBP => 'image/webp',
            self::AVIF => 'image/avif'
        };
    }
}
