<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template Tstring of string
 * @extends Min<Tstring, int<0, max>>
 */
final class MinLength extends Min
{
    /**
     * @param positive-int $max
     * @param non-empty-string|null $measuredEncoding
     * @param non-empty-string|null $sourceEncoding
     * @return Rule<Tstring, Tstring>
     */
    public function maxByte(int $max, ?string $measuredEncoding = null, ?string $sourceEncoding = null): Rule
    {
        if ($this->comparedTo >= $max) {
            throw new \LogicException('Max value rule parameter must be greater than min value');
        }

        $stringLength = new StringLength($measuredEncoding, $sourceEncoding);
        /** @var Max<Tstring, positive-int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $stringLength,
            new CastString(),
            $max,
            $stringLength->convertedName()
        );
    }

    /**
     * @param positive-int $max
     * @param non-empty-string $encoding
     * @return Rule<Tstring, Tstring>
     */
    public function maxChar(int $max, string $encoding = 'UTF-8'): Rule
    {
        if ($this->comparedTo >= $max) {
            throw new \LogicException('Max value rule parameter must be greater than min value');
        }

        /** @var Max<Tstring, positive-int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new CharacterCount($encoding),
            new CastString(),
            $max,
            'number of ' . $encoding . ' encoded chars'
        );
    }
}
