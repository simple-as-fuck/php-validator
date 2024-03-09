<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @template TComponent
 * @extends ReadableRule<array<non-empty-string, TComponent>, TComponent>
 */
final class Component extends ReadableRule
{
    /** @var Key<TComponent> */
    private readonly Key $key;

    /**
     * @param RuleChain<array<non-empty-string, TComponent>> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $componentName)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        /** @var RuleChain<array<TComponent>> $ruleChain */
        $this->key = new Key($exceptionFactory, $ruleChain, $validated, $valueName, $componentName);
    }

    /**
     * @param array<non-empty-string, TComponent> $value
     * @return TComponent|null
     */
    protected function validate($value): mixed
    {
        return $this->key->validate($value);
    }
}
