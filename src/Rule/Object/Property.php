<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
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
    /**
     * @param RuleChain<object> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private string $propertyName,
        private bool $present = false
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    public function string(bool $emptyAsNull = false): StringRule
    {
        return new StringRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName, $emptyAsNull);
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName);
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName);
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName);
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName);
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'->'.$this->propertyName);
    }

    /**
     * @param object $value
     * @return mixed|null
     */
    protected function validate($value)
    {
        if ($this->present && ! property_exists($value, $this->propertyName)) {
            throw new ValueMust('contain property: '.$this->propertyName);
        }

        return \get_object_vars($value)[$this->propertyName] ?? null;
    }
}
