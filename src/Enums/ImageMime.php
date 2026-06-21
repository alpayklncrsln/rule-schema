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
    case BMP = 'bmp';
    case ICO = 'ico';
    case TIFF = 'tiff';
    case APNG = 'apng';

    public function type(): string
    {
        return match ($this) {
            self::PNG => 'image/png',
            self::JPG, self::JPEG => 'image/jpeg',
            self::GIF => 'image/gif',
            self::SVG => 'image/svg+xml',
            self::WEBP => 'image/webp',
            self::AVIF => 'image/avif',
            self::BMP => 'image/bmp',
            self::ICO => 'image/vnd.microsoft.icon',
            self::TIFF => 'image/tiff',
            self::APNG => 'image/apng',
        };
    }
}
