<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TValue
 * @extends Rule<array<TValue>, TValue>
 */
class Key extends Rule
{
    /**
     * @param RuleChain<array<TValue>> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private int|string $key
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param array<TValue> $value
     * @return TValue|null
     */
    protected function validate($value)
    {
        if (! array_key_exists($this->key, $value)) {
            return null;
        }

        return $value[$this->key];
    }
}
