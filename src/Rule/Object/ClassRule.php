<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Rule\Custom\UserClassRule;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @template TClass of object
 * @extends ReadableRule<object, TClass>
 */
final class ClassRule extends ReadableRule
{
    /**
     * @param UserClassRule<TClass> $classRule
     */
    public function __construct(
        private readonly ObjectRule $rule,
        private readonly UserClassRule $classRule
    ) {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $rule->valueName());
    }

    /**
     * @param object $value
     * @return TClass|null
     */
    protected function validate($value)
    {
        return $this->classRule->validate($this->rule);
    }
}
