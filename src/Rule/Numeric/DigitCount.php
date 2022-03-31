<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\Compared;

/**
 * @extends Compared<numeric-string, int<0, max>>
 */
final class DigitCount extends Compared
{
    /**
     * @param numeric-string $comparedValue
     * @return int<0, max>
     */
    public function convert($comparedValue): int
    {
        $comparedValue = ltrim($comparedValue, '-');
        $decimalSeparatorPos = strpos($comparedValue, '.');
        if ($decimalSeparatorPos === false) {
            return strlen($comparedValue);
        }

        return $decimalSeparatorPos;
    }
}
