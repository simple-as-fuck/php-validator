<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends ReadableRule<string, int>
 */
final class ParseInt extends ReadableRule
{
    /**
     * @param Rule<mixed, string> $rule
     */
    public function __construct(Rule $rule, string $valueName)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $valueName);
    }

    /**
     * @return MinWithMax<int, int>
     */
    public function min(int $min): MinWithMax
    {
        return new MinWithMax($this, $this->valueName(), new ComparedValue(), new CastString(), $min);
    }

    /**
     * @return Max<int, int>
     */
    public function max(int $max): Max
    {
        return new Max($this, $this->valueName(), new ComparedValue(), new CastString(), $max);
    }

    /**
     * @param string $value
     */
    protected function validate($value): int
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new ValueMust('be parsable as integer');
        }

        return (int) $value;
    }
}
