<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserClassRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<mixed, array<mixed>>
 */
final class ArrayRule extends Rule
{
    public function key(string $key): TypedKey
    {
        return new TypedKey($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().'['.$key.']', $key);
    }

    /**
     * @template TOut
     * @param callable(TypedKey): TOut $callable
     * @return TOut|null
     */
    public function nullable(string $key, callable $callable)
    {
        return $this->validateChain() === null ? null : $callable($this->key($key));
    }

    /**
     * @template TMapped
     * @param callable(TypedKey): TMapped $callable
     * @return Collection<TMapped>
     */
    public function of(callable $callable): Collection
    {
        return new Collection($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), $callable);
    }

    /**
     * @template TClass of object
     * @param UserClassRule<TClass> $rule
     * @return Collection<TClass>
     */
    public function ofClass(UserClassRule $rule): Collection
    {
        return new Collection($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), fn (TypedKey $key) => $key->object()->class($rule)->notNull());
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        if (! is_array($value)) {
            throw new ValueMust('be array');
        }

        return $value;
    }
}
