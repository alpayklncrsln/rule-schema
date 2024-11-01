<?php

namespace Alpayklncrsln\RuleSchema\Enums;

use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Alpayklncrsln\RuleSchema\Traits\MimeTrait;

enum FileMime: string implements MimeEnumInterface
{
    use MimeTrait;

    case AAC = 'aac';
    case ABW = 'abw';
    case APNG = 'apng';

    case ARC = 'arc';

    case AVIF = 'avif';

    case AVI = 'avi';
    case AZW = 'azw';

    case BIN = 'bin';

    case BMP = 'bmp';

    case BZ = 'bz';

    case BZ2 = 'bz2';

    case CDA = 'cda';

    case CSH = 'csh';

    case CSS = 'css';

    case CSV = 'csv';

    case DOC = 'doc';

    case DOCX = 'docx';

    case EOT = 'eot';

    case EPUB = 'epub';

    case GZ = 'gz';
    case GIF = 'gif';

    case HTM = 'htm';
    case HTML = 'html';

    case ICO = 'ico';

    case ICS = 'ics';

    case JAR = 'jar';

    case JPEG = 'jpeg';

    case JPG = 'jpg';

    case JS = 'js';

    case JSON = 'json';
    case JSONLD = 'jsonld';

    case MID = 'mid';
    case  MIDI = 'midi';
    case MJS = 'mjs';
    case MP3 = 'mp3';
    case MP4 = 'mp4';
    case MPEG = 'mpeg';
    case MPKG = 'mpkg';
    case ODP = 'odp';
    case ODS = 'ods';
    case ODT = 'odt';
    case OGA = 'oga';
    case OGV = 'ogv';
    case OGX = 'ogx';
    case OPUS = 'opus';
    case OTF = 'otf';
    case PNG = 'png';
    case PDF = 'pdf';

    case PHP = 'php';
    case PPT = 'ppt';
    case PPTX = 'pptx';
    case RAR = 'rar';
    case RTF = 'rtf';
    case SH = 'sh';
    case SVG = 'svg';
//    case SWF = 'swf';
    case TAR = 'tar';
    case TIFF = 'tiff';
    case TS = 'ts';
    case TTF = 'ttf';
    case TXT = 'txt';
    case VSD = 'vsd';
    case WAV = 'wav';
    case WEBA = 'weba';
    case WEBM = 'webm';
    case WEBP = 'webp';
    case WOFF = 'woff';
    case WOFF2 = 'woff2';
    case XHTML = 'xhtml';
    case XLS = 'xls';
    case XLSX = 'xlsx';
    case XML = 'xml';
    case XUL = 'xul';
    case XZ = 'xz';
    case ZIP = 'zip';
    case _3GP = '3gp';
    case _3G2 = '3g2';
    case _7Z = '7z';


    public function type(): string
    {
        return match ($this) {
            self::AAC => 'audio/aac',
            self::ABW => 'application/x-abiword',
            self::APNG => 'image/apng',
            self::ARC => 'application/x-freearc',
            self::AVIF => 'image/avif',
            self::AVI => 'video/x-msvideo',
            self::AZW => 'application/vnd.amazon.ebook',
            self::BIN => 'application/octet-stream',
            self::BMP => 'image/bmp',
            self::BZ => 'application/x-bzip',
            self::BZ2 => 'application/x-bzip2',
            self::CDA => 'application/x-cdf',
            self::CSH => 'application/x-csh',
            self::CSS => 'text/css',
            self::CSV => 'text/csv',
            self::DOC => 'application/msword',
            self::DOCX => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            self::EOT => 'application/vnd.ms-fontobject',
            self::EPUB => 'application/epub+zip',
            self::GZ => 'application/gzip',
            self::GIF => 'image/gif',
            self::ICO => 'image/vnd.microsoft.icon',
            self::HTM, self::HTML => 'text/html',
            self::ICS => 'text/calendar',
            self::JAR => 'application/java-archive',
            self::JPEG,
            self::JPG => 'image/jpeg',
            self::JS => 'application/javascript',
            self::JSON => 'application/json',
            self::JSONLD => 'application/ld+json',
            self::MID,
            self::MIDI => 'audio/midi',
            self::MJS => 'text/javascript',
            self::MP3 => 'audio/mpeg',
            self::MP4 => 'video/mp4',
            self::MPEG => 'video/mpeg',
            self::MPKG => 'application/vnd.apple.installer+xml',
            self::ODP => 'application/vnd.oasis.opendocument.presentation',
            self::ODS => 'application/vnd.oasis.opendocument.spreadsheet',
            self::ODT => 'application/vnd.oasis.opendocument.text',

            self::OPUS,
            self::OGA => 'audio/ogg',
            self::OGV => 'video/ogg',
            self::OGX => 'application/ogg',

            self::OTF => 'font/otf',

            self::PNG => 'image/png',
            self::PDF => 'application/pdf',

            self::PHP => 'application/x-httpd-php',

            self::PPT => 'application/vnd.ms-powerpoint',
            self::PPTX => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            self::RAR => 'application/vnd.rar',
            self::RTF => 'application/rtf',
            self::SH => 'application/x-sh',
            self::SVG => 'image/svg+xml',
            self::TAR => 'application/x-tar',
            self::TIFF => 'image/tiff',
            self::TS => 'video/mp2t',
            self::TTF => 'font/ttf',
            self::TXT => 'text/plain',
            self::VSD => 'application/vnd.visio',
            self::WAV => 'audio/wav',
            self::WEBA => 'audio/webm',
            self::WEBM => 'video/webm',
            self::WEBP => 'image/webp',
            self::WOFF => 'font/woff',
            self::WOFF2 => 'font/woff2',
            self::XHTML => 'application/xhtml+xml',
            self::XLS => 'application/vnd.ms-excel',
            self::XLSX => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            self::XML => 'application/xml',
            self::XUL => 'application/vnd.mozilla.xul+xml',
            self::ZIP => 'application/zip',

            self::_3GP => 'video/3gpp',
            self::_3G2 => 'video/3gpp2',
            self::_7Z => 'application/x-7z-compressed',
            self::XZ => throw new \Exception('To be implemented'),

        };
    }
}
