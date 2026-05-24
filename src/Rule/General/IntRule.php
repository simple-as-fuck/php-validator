<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Rule\Common\NotIn;
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
     * @param array<int> $values
     * @return Rule<int, int>
     */
    final public function notIn(array $values): Rule
    {
        return new NotIn(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values,
        );
    }

    /**
     * @template TEnum of \BackedEnum of int
     * @param class-string<TEnum> $enumClass
     * @return Rule<int, value-of<TEnum>>
     */
    public function inEnum(string $enumClass): Rule
    {
        if (((string) (new \ReflectionEnum($enumClass))->getBackingType()) !== 'int') {
            throw new \LogicException('BackedEnum: '.$enumClass.' must be of type integer');
        }

        /** @phpstan-ignore-next-line */
        return $this->in(array_map(static fn (\BackedEnum $enum): int => (int) $enum->value, $enumClass::cases()));
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
        /** @phpstan-ignore-next-line return.type */
        return $this->min(1);
    }

    /**
     * @return Rule<int, non-zero-int>
     */
    final public function notZero(): Rule
    {
        /** @phpstan-ignore-next-line return.type */
        return $this->notIn([0]);
    }
}
