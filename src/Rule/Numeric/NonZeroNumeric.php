<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends Numeric<numeric-string>
 */
final class NonZeroNumeric extends Numeric
{
    /**
     * @param numeric-string $value
     * @return numeric-string
     */
    protected function validate($value): string
    {
        $zeroChars = ['0', '-', '.'];
        foreach (str_split($value) as $char) {
            if (in_array($char, $zeroChars, true)) {
                continue;
            }

            return $value;
        }

        throw new ValueMust('be non zero numeric string');
    }
}
