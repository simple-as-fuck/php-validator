<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\Min;
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
        return new Same($this, $this->valueName().' array size', new ArraySize(), $size);
    }

    /**
     * @param positive-int $min
     * @return Min<array<TOut>, int>
     */
    public function min(int $min): Min
    {
        return new Min($this, $this->valueName().' array size', new ArraySize(), $min);
    }

    /**
     * @param positive-int $max
     * @return ArrayMax<TOut>
     */
    public function max(int $max): ArrayMax
    {
        return new ArrayMax($this, $this->valueName().' array size', new ArraySize(), $max);
    }

    /**
     * @return array<TOut>
     */
    public function notNull(): array
    {
        return $this->validateChain() ?? [];
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
