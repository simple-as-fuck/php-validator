<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Custom;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @template TIn
 * @template TOut
 * @extends ReadableRule<TIn, TOut>
 */
final class CustomRule extends ReadableRule
{
    /**
     * @param RuleChain<TIn> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param UserDefinedRule<TIn, TOut> $userDefinedRule
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly UserDefinedRule $userDefinedRule
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param TIn $value
     * @return TOut|null
     */
    protected function validate($value)
    {
        return $this->userDefinedRule->validate($value);
    }
}
