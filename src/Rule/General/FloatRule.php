<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TIn
 * @extends ReadableRule<TIn, float>
 */
abstract class FloatRule extends ReadableRule
{
    /**
     * @return MinWithMax<float, float>
     */
    final public function min(float $min): MinWithMax
    {
        /** @var MinWithMax<float, float> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new ComparedValue(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min
        );
        return $minRule;
    }

    /**
     * @return Max<float, float>
     */
    final public function max(float $max): Max
    {
        /** @var Max<float, float> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new ComparedValue(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max
        );
        return $maxRule;
    }
}
