<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;

/**
 * @extends Max<numeric-string, int<0, max>>
 */
final class MaxDigit extends Max
{
    /**
     * @param int<0,max> $max maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function maxDecimal(int $max): Max
    {
        /** @var Max<numeric-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new DecimalCount(),
            new CastString(),
            $max,
            'decimal digits'
        );
        return $maxRule;
    }
}
