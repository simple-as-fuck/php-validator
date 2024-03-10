<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TValue
 * @template TCompared
 * @extends Min<TValue, TCompared>
 */
final class MinWithMax extends Min
{
    /**
     * @param TCompared $maxValue
     * @return Max<TValue, TCompared>
     */
    public function max($maxValue): Max
    {
        if ($this->comparedTo >= $maxValue) {
            throw new \LogicException('Max value rule parameter must be greater than min value');
        }

        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $this->conversion,
            $this->toString,
            $maxValue,
            $this->comparedName
        );
    }
}
