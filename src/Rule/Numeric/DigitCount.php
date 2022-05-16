<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<numeric-string, int<0, max>>
 */
final class DigitCount extends Conversion
{
    /**
     * @param numeric-string $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        $value = ltrim($value, '-');
        $decimalSeparatorPos = strpos($value, '.');
        if ($decimalSeparatorPos === false) {
            return strlen($value);
        }

        return $decimalSeparatorPos;
    }
}
