<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<mixed, array<mixed>>
 */
final class ArrayOfString extends Rule
{
    private readonly ArrayRule $arrayRule;

    /**
     * @param RuleChain<mixed> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->arrayRule = new ArrayRule($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    public function key(string $key): StringTypedKey
    {
        $typedKey = $this->arrayRule->key($key);
        return new StringTypedKey($this->exceptionFactory, $this->ruleChain(), $this->validated, $typedKey->valueName, $typedKey);
    }

    /**
     * @template TMapped
     * @param callable(StringTypedKey): TMapped $callable
     * @return Collection<TMapped>
     */
    public function of(callable $callable): Collection
    {
        return $this->arrayRule->of(
            fn (TypedKey $typedKey) => $callable(
                new StringTypedKey($this->exceptionFactory, $this->ruleChain(), $this->validated, $typedKey->valueName, $typedKey)
            )
        );
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        return $this->arrayRule->validate($value);
    }
}
