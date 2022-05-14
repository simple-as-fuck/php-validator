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

final class Rules
{
    private ?Exception $exceptionFactory;
    /** @var non-empty-string */
    private string $valueName;
    /** @var Validated<mixed> */
    private $validated;

    /**
     * @param non-empty-string $valueName
     * @param Validated<mixed> $value
     */
    public function __construct(?Exception $exceptionFactory, string $valueName, Validated $value)
    {
        $this->exceptionFactory = $exceptionFactory;
        $this->valueName = $valueName;
        $this->validated = $value;
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function string(): StringRule
    {
        return new StringRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }

    /**
     * @template TOut
     * @param callable(ObjectRule): TOut $callable
     * @return TOut|null
     */
    public function nullable(callable $callable)
    {
        return $this->validated->value() === null ? null : $callable($this->object());
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory, new RuleChain(), $this->validated, $this->valueName);
    }
}
