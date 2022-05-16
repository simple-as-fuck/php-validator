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
     * @param string $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        return strlen($value);
    }
}
