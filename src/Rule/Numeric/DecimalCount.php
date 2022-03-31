<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\Compared;

/**
 * @extends Compared<numeric-string, int<0, max>>
 */
final class DecimalCount extends Compared
{
    /**
     * @param numeric-string $comparedValue
     * @return int<0, max>
     */
    public function convert($comparedValue): int
    {
        $decimalSeparatorPos = strpos($comparedValue, '.');
        if ($decimalSeparatorPos === false) {
            return 0;
        }

        /** @var int<0, max> $digitCount */
        $digitCount = strlen($comparedValue) - $decimalSeparatorPos - 1;
        return $digitCount;
    }
}
