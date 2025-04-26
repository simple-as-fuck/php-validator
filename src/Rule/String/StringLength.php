<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<string, int<0, max>>
 */
final class StringLength extends Conversion
{
    /**
     * @param non-empty-string|null $measuredEncoding
     * @param non-empty-string|null $sourceEncoding
     */
    public function __construct(
        private readonly ?string $measuredEncoding = null,
        private readonly ?string $sourceEncoding = null,
    ) {
    }

    /**
     * @param string $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        if ($this->measuredEncoding !== null) {
            $value = \mb_convert_encoding($value, $this->measuredEncoding, $this->sourceEncoding);
        }

        return strlen($value);
    }

    /**
     * @return non-empty-string
     */
    public function convertedName(): string
    {
        return ($this->measuredEncoding !== null ? $this->measuredEncoding . ' ' : '') . 'string length in bytes';
    }
}
