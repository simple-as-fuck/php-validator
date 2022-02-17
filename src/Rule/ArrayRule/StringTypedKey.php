<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @extends Rule<array<mixed>, mixed>
 */
final class StringTypedKey extends Rule
{
    /** @var Key<mixed> */
    private Key $keyRule;

    /**
     * @param Key<mixed> $keyRule
     */
    public function __construct(RuleChain $ruleChain, Key $keyRule)
    {
        parent::__construct($keyRule->exceptionFactory(), $ruleChain, $keyRule->validated(), $keyRule->valueName());
        $this->keyRule = $keyRule;
    }

    public function string(): StringRule
    {
        return new StringRule($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function array(): ArrayOfString
    {
        return new ArrayOfString($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    /**
     * @param array<mixed> $value
     * @return mixed|null
     */
    protected function validate($value)
    {
        return $this->keyRule->validate($value);
    }
}
