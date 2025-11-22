<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\ArrayRule;
use SimpleAsFuck\Validator\Rule\Custom\CallableRule;
use SimpleAsFuck\Validator\Rule\Custom\CustomRule;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;
use SimpleAsFuck\Validator\Rule\Numeric\BoolRule;
use SimpleAsFuck\Validator\Rule\Numeric\FloatRule;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

final readonly class Rules
{
    /**
     * @param non-empty-string $valueName
     * @param Validated<mixed> $validated
     */
    public function __construct(
        private Exception $exceptionFactory,
        private string $valueName,
        private Validated $validated
    ) {
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function string(bool $sensitiveValue = false): StringRule
    {
        return new StringRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName, $sensitiveValue);
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    /**
     * @template TCustomOut
     * @param UserDefinedRule<mixed, TCustomOut> $rule
     * @return CustomRule<mixed, TCustomOut>
     */
    public function custom(UserDefinedRule $rule): CustomRule
    {
        return new CustomRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName, $rule);
    }

    /**
     * @template TCallableOut
     * @param callable(mixed): TCallableOut $callable
     * @return CallableRule<mixed, TCallableOut>
     */
    public function callable(callable $callable): CallableRule
    {
        return new CallableRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName, $callable);
    }
}
