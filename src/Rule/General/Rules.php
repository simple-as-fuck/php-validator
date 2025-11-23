<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\ArrayRule;
use SimpleAsFuck\Validator\Rule\Numeric\BoolRule;
use SimpleAsFuck\Validator\Rule\Numeric\FloatRule;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @extends Rule<mixed, mixed>
 */
final class Rules extends Rule
{
    /**
     * @param non-empty-string $valueName
     * @param Validated<mixed> $validated
     */
    public function __construct(
        Exception $exceptionFactory,
        string $valueName,
        Validated $validated,
    ) {
        parent::__construct($exceptionFactory, new RuleChain(), $validated, $valueName);
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function string(bool $sensitiveValue = false): StringRule
    {
        return new StringRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $sensitiveValue);
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): mixed
    {
        return $value;
    }
}
