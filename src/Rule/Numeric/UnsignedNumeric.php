<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends Numeric<numeric-string>
 */
final class UnsignedNumeric extends Numeric
{
    /**
     * @return Numeric<numeric-string>
     */
    public function nonZero(): Numeric
    {
        return new NonZeroNumeric($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @param numeric-string $value
     * @return numeric-string
     */
    protected function validate($value): string
    {
        if (str_contains($value, '-')) {
            throw new ValueMust('be unsigned numeric string');
        }

        return $value;
    }
}
