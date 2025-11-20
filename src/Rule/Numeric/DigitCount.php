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
        /** @phpstan-ignore-next-line */
        $decimalSeparatorPos = strpos($value, '.');
        if ($decimalSeparatorPos === false) {
            /** @phpstan-ignore-next-line */
            return strlen($value);
        }

        return $decimalSeparatorPos;
    }
}
