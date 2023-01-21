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
    private ObjectRule $rule;
    /** @var UserClassRule<TClass> */
    private UserClassRule $classRule;

    /**
     * @param UserClassRule<TClass> $classRule
     */
    public function __construct(ObjectRule $rule, UserClassRule $classRule)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $rule->valueName());
        $this->rule = $rule;
        $this->classRule = $classRule;
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
