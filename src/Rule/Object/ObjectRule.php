<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserClassRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<mixed, object>
 */
final class ObjectRule extends Rule
{
    /**
     * @template TClass of object
     * @param UserClassRule<TClass> $rule
     * @return ClassRule<TClass>
     */
    public function class(UserClassRule $rule): ClassRule
    {
        return new ClassRule($this, $rule);
    }

    public function property(string $name): Property
    {
        return new Property($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), $name);
    }

    /**
     * @template TOut
     * @param callable(Property): TOut $callable
     * @return TOut|null
     */
    public function nullable(string $name, callable $callable)
    {
        return $this->validateChain() === null ? null : $callable($this->property($name));
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): object
    {
        if (! is_object($value)) {
            throw new ValueMust('be object, '.gettype($value).' given');
        }

        return $value;
    }
}
