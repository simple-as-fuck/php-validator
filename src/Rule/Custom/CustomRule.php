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
    /** @var UserDefinedRule<TIn, TOut> */
    private UserDefinedRule $userDefinedRule;

    /**
     * @param RuleChain<TIn> $ruleChain
     * @param Validated<mixed> $validated
     * @param UserDefinedRule<TIn, TOut> $userDefinedRule
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, UserDefinedRule $userDefinedRule)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->userDefinedRule = $userDefinedRule;
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
