<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\General\Same;

/**
 * @template TOut
 * @extends ReadableRule<array<mixed>, array<TOut>>
 */
final class Collection extends ReadableRule
{
    /** @var Rule<mixed, array<mixed>> */
    private Rule $rule;
    /** @var callable(TypedKey): TOut  */
    private $callable;

    /**
     * @param Rule<mixed, array<mixed>> $rule
     * @param callable(TypedKey): TOut $callable
     */
    public function __construct(Rule $rule, callable $callable)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $rule->valueName());
        $this->rule = $rule;
        $this->callable = $callable;
    }

    /**
     * @param positive-int $size
     * @return Same<array<TOut>, int>
     */
    public function size(int $size): Same
    {
        /** @phpstan-ignore-next-line */
        return new Same($this, $this->valueName(), new ArraySize(), $size, 'array size');
    }

    /**
     * @param positive-int $min
     * @return MinWithMax<array<TOut>, int>
     */
    public function min(int $min): MinWithMax
    {
        /** @phpstan-ignore-next-line */
        return new MinWithMax($this, $this->valueName(), new ArraySize(), new CastString(), $min, 'array size');
    }

    /**
     * @param positive-int $max
     * @return ArrayMax<TOut>
     */
    public function max(int $max): ArrayMax
    {
        /** @phpstan-ignore-next-line */
        return new ArrayMax($this, $this->valueName(), new ArraySize(), new CastString(), $max, 'array size');
    }

    /**
     * @return array<TOut>
     */
    public function notNull(bool $failAsEmpty = false): array
    {
        return $this->validateChain($failAsEmpty) ?? [];
    }

    /**
     * @param array<mixed> $value
     * @return array<TOut>
     */
    final protected function validate($value): array
    {
        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = ($this->callable)(new TypedKey($this->exceptionFactory(), $this->rule->ruleChain(), $this->validated(), $this->valueName().'['.$key.']', $key));
        }


        return $result;
    }
}
