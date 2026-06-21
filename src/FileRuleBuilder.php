<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Enums\AudioMime;
use Alpayklncrsln\RuleSchema\Enums\FileMime;
use Alpayklncrsln\RuleSchema\Enums\ImageMime;
use Alpayklncrsln\RuleSchema\Interfaces\MimeEnumInterface;
use Illuminate\Validation\Rules\Dimensions;

class FileRuleBuilder extends BaseRuleBuilder
{
    public function __construct(string $attribute = '')
    {
        parent::__construct($attribute);
        $this->file();
    }

    public function file(bool $check = true, ?string $message = null): self
    {
        $this->rule['file'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function image(): self
    {
        $this->rule['image'] = true;

        return $this;
    }

    public function mimes(?string $message = null, MimeEnumInterface|string ...$mimes): self
    {
        $this->rule['mimes'] = implode(',', array_map(
            fn($mime) => is_string($mime) ? $mime : $mime->getValue(),
            $mimes
        ));

        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function mimetypes(?string $message = null, string|MimeEnumInterface ...$mimetypes): self
    {
        $this->rule['mimetypes'] = implode(',', array_map(
            fn($mimeType) => is_string($mimeType) ? $mimeType : $mimeType->getValue(),
            $mimetypes
        ));

        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function dimensions(string|Dimensions $value, ?string $message = null): self
    {
        $this->rule['dimensions'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function dimensionsImageWidthHeight(int $width, int $height, ?string $message = null): self
    {
        $this->image();
        $this->dimensions('width:' . $width . ',height:' . $height);
        $this->setMessage('dimensions', $message);

        return $this;
    }

    public function dimensionsImageMinWidthMinHeight(int $minWidth, int $minHeight, ?string $message = null): self
    {
        $this->image();
        $this->dimensions('min_width:' . $minWidth . ',min_height:' . $minHeight);
        $this->setMessage('dimensions', $message);

        return $this;
    }

    public function mimeAndMimetypes(?string $message = null, MimeEnumInterface ...$mimes): self
    {
        $this->mimes(...array_map(fn($mime) => $mime->getValue(), $mimes));
        $this->mimetypes(...array_map(fn($mime) => $mime->type(), $mimes));
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function max(int $max, ?string $message = null): self
    {
        $this->rule['max'] = $max;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function min(int $min, ?string $message = null): self
    {
        $this->rule['min'] = $min;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function size(int $size, ?string $message = null): self
    {
        $this->rule['size'] = $size;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function between(int $min, int $max, ?string $message = null): self
    {
        $this->rule['between'] = "$min,$max";
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function audioOnly(?string $message = null): self
    {
        $this->mimes($message, ...AudioMime::cases());

        return $this;
    }

    public function imageOnly(?string $message = null): self
    {
        $this->mimes($message, ...ImageMime::cases());

        return $this;
    }

    public function documentOnly(?string $message = null): self
    {
        $this->mimes($message,
            FileMime::PDF,
            FileMime::DOC,
            FileMime::DOCX,
            FileMime::XLS,
            FileMime::XLSX,
            FileMime::PPT,
            FileMime::PPTX,
            FileMime::TXT,
            FileMime::RTF
        );

        return $this;
    }

    public function archiveOnly(?string $message = null): self
    {
        $this->mimes($message,
            FileMime::ZIP,
            FileMime::TAR,
            FileMime::GZ,
            FileMime::RAR,
            FileMime::_7Z
        );

        return $this;
    }
}
