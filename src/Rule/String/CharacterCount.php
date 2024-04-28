<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<string, int<0, max>>
 */
final class CharacterCount extends Conversion
{
    /**
     * @param non-empty-string $encoding
     */
    public function __construct(
        private readonly string $encoding,
    ) {
    }

    /**
     * @param string $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        return mb_strlen($value, $this->encoding);
    }
}
