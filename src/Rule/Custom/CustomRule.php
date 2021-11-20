<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Custom;

use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

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
     * @param Rule<mixed, TIn> $rule
     * @param UserDefinedRule<TIn, TOut> $userDefinedRule
     */
    public function __construct(Rule $rule, UserDefinedRule $userDefinedRule)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $rule->valueName());
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
