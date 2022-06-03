<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, numeric-string>
 */
final class ParseNumeric extends ReadableRule
{
    /**
     * @param positive-int $max maximum digits before decimal separator, without minus sign
     */
    public function maxDigit(int $max): MaxDigit
    {
        return new MaxDigit(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new DigitCount(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max,
            'digits before decimals'
        );
    }

    /**
     * @param int<0,max> $max maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function maxDecimal(int $max): Max
    {
        /** @var Max<numeric-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new DecimalCount(),
            new CastString(),
            $max,
            'decimal digits'
        );
        return $maxRule;
    }

    /**
     * @param string $value
     * @return numeric-string
     */
    protected function validate($value): string
    {
        if (preg_match('/^-?(0|[1-9]\d*)(\.\d+)?$/', $value) !== 1) {
            throw new ValueMust('be parsable as number in decimal system');
        }

        /** @var numeric-string $value */
        return $value;
    }
}
