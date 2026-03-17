<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Common;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template Tvalue of int|float|string|\Stringable
 * @extends Rule<Tvalue, Tvalue>
 */
final class NotIn extends Rule
{
    /**
     * @param RuleChain<covariant Tvalue> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param array<Tvalue> $values
     */
    public function __construct(
        Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly array $values,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param Tvalue $value
     * @return Tvalue
     */
    protected function validate($value): mixed
    {
        if (in_array($value, $this->values, true)) {
            throw new ValueMust('not be in values list: '.implode(', ', $this->values));
        }

        return $value;
    }
}
