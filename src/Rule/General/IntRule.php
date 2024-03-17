<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Rule\Enum\Enum;

/**
 * @template TIn
 * @extends Rule<TIn, int>
 */
abstract class IntRule extends Rule
{
    /**
     * @return MinWithMax<int, int>
     */
    final public function min(int $min): MinWithMax
    {
        /** @var MinWithMax<int, int> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min
        );
        return $minRule;
    }

    /**
     * @return Max<int, int>
     */
    final public function max(int $max): Max
    {
        /** @var Max<int, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max
        );
        return $maxRule;
    }

    /**
     * @template Tint of int
     * @param non-empty-array<Tint> $values
     * @return InRule<int, Tint>
     */
    final public function in(array $values): InRule
    {
        return new InRule(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values
        );
    }

    /**
     * @template TEnum of \BackedEnum of int
     * @param class-string<TEnum> $enumClass
     * @return Enum<TEnum>
     */
    public function enum(string $enumClass): Enum
    {
        if (((string) (new \ReflectionEnum($enumClass))->getBackingType()) !== 'int') {
            throw new \LogicException('BackedEnum: '.$enumClass.' must be of type integer');
        }

        return new Enum($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $enumClass);
    }

    /**
     * @return MinWithMax<positive-int, int>
     */
    final public function positive(): MinWithMax
    {
        /** @var MinWithMax<positive-int, int> */
        return $this->min(1);
    }
}
