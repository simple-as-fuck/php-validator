<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<numeric-string, int<0, max>>
 */
final class DecimalCount extends Conversion
{
    /**
     * @param numeric-string $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        $decimalSeparatorPos = strpos($value, '.');
        if ($decimalSeparatorPos === false) {
            return 0;
        }

        /** @var int<0, max> $digitCount */
        $digitCount = strlen($value) - $decimalSeparatorPos - 1;
        return $digitCount;
    }
}
