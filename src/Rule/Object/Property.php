<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\ArrayRule;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\Numeric\BoolRule;
use SimpleAsFuck\Validator\Rule\Numeric\FloatRule;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @extends Rule<object, mixed>
 */
final class Property extends Rule
{
    private string $propertyName;

    /**
     * @param RuleChain<object> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $propertyName)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName.'->'.$propertyName);
        $this->propertyName = $propertyName;
    }

    public function string(bool $emptyAsNull = false): StringRule
    {
        return new StringRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), false, $emptyAsNull);
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    /**
     * @param object $value
     * @return mixed|null
     */
    protected function validate($value)
    {
        $properties = \get_object_vars($value);
        if (\array_key_exists($this->propertyName, $properties)) {
            return $properties[$this->propertyName];
        }

        return null;
    }
}
