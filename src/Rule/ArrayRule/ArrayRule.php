<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserClassRule;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;

/**
 * @extends Rule<mixed, array<mixed>>
 */
final class ArrayRule extends Rule
{
    /**
     * @param string|int $key
     */
    public function key($key): TypedKey
    {
        $valueName = $this->valueName;
        $array = $this->nullable(failAsNull: true);
        if ($array !== null) {
            if (\count($array) > 10) {
                $keys = \array_keys(\array_slice($array, 0, 10, true));
                $keys[] =  '...';
            } else {
                $keys = \array_keys($array);
            }
            $valueName .= '{'.\implode(',', $keys).'}';
        }
        $valueName .= '['.$key.']';
        return new TypedKey($this->exceptionFactory, $this->ruleChain(), $this->validated, $valueName, $key);
    }

    /**
     * @template TMapped
     * @param callable(TypedKey): TMapped $callable
     * @return Collection<TMapped>
     */
    public function of(callable $callable): Collection
    {
        return new Collection($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $callable);
    }

    /**
     * @template TClass of object
     * @param UserClassRule<TClass> $rule
     * @return Collection<TClass>
     */
    public function ofClass(UserClassRule $rule): Collection
    {
        return $this->of(static fn (TypedKey $key) => $key->object()->class($rule)->notNull());
    }

    /**
     * @return Collection<int>
     */
    public function ofInt(): Collection
    {
        return $this->of(static fn (TypedKey $key): int => $key->int()->notNull());
    }

    /**
     * @return Collection<positive-int>
     */
    public function ofPositiveInt(): Collection
    {
        /** @var Collection<positive-int> */
        return $this->of(static fn (TypedKey $key): int => $key->int()->positive()->notNull());
    }

    /**
     * @return Collection<bool>
     */
    public function ofBool(): Collection
    {
        return $this->of(static fn (TypedKey $key): bool => $key->bool()->notNull());
    }

    /**
     * @return Collection<string>
     */
    public function ofString(bool $sensitiveValues = false): Collection
    {
        return $this->of(static fn (TypedKey $key): string => $key->string($sensitiveValues)->notNull());
    }

    /**
     * @return Collection<non-empty-string>
     */
    public function ofNonEmptyString(bool $sensitiveValues = false): Collection
    {
        return $this->of(static fn (TypedKey $key): string => $key->string($sensitiveValues)->notEmpty()->notNull());
    }

    /**
     * @template TEnum of \BackedEnum of string
     * @param class-string<TEnum> $enumClass
     * @return Collection<TEnum>
     */
    public function ofParseEnum(string $enumClass): Collection
    {
        return $this->of(static fn (TypedKey $key): \BackedEnum => $key->string()->parseEnum($enumClass)->notNull());
    }

    /**
     * @return Collection<ObjectRule>
     */
    public function ofObject(): Collection
    {
        return $this->of(static fn (TypedKey $key): ObjectRule => $key->object());
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        if (!is_array($value)) {
            throw new ValueMust('be array, '.gettype($value).' given');
        }

        return $value;
    }
}
