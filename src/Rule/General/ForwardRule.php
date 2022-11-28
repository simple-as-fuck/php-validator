<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;

/**
 * @template TIn
 * @template TOut
 * @extends ReadableRule<TIn, TOut>
 */
abstract class ForwardRule extends ReadableRule
{
    /**
     * @param RuleChain<TIn> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param Rule<TIn, TOut> $forwardedRule
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private Rule $forwardedRule
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param TIn $value
     * @return TOut|null
     */
    protected function validate($value)
    {
        return $this->forwardedRule->validate($value);
    }
}
