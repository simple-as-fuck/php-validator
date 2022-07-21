<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\Min;

/**
 * @extends Min<numeric-string, int<0, max>>
 */
final class MinDigit extends Min
{
    /**
     * @param positive-int $max maximum digits before decimal separator, without minus sign
     */
    public function maxDigit(int $max): MaxDigit
    {
        if ($this->comparedTo() >= $max) {
            throw new \LogicException('Max value rule parameter must be greater than min value');
        }

        return new MaxDigit(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            $this->conversion(),
            $this->toString(),
            $max,
            $this->comparedName()
        );
    }
}
