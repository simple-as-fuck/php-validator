<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\Numeric\BoolRule;
use SimpleAsFuck\Validator\Rule\Numeric\FloatRule;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @extends Key<mixed>
 */
final class TypedKey extends Key
{
    public function string(): StringRule
    {
        return new StringRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
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
}
