<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends ReadableRule<string, float>
 */
final class ParseFloat extends ReadableRule
{
    /**
     * @param Rule<mixed, string> $rule
     */
    public function __construct(Rule $rule, string $valueName)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $valueName);
    }

    /**
     * @return MinWithMax<float, float>
     */
    public function min(float $min): MinWithMax
    {
        return new MinWithMax($this, $this->valueName(), new ComparedValue(), new CastString(), $min);
    }

    /**
     * @return Max<float, float>
     */
    public function max(float $max): Max
    {
        return new Max($this, $this->valueName(), new ComparedValue(), new CastString(), $max);
    }

    /**
     * @param string $value
     */
    protected function validate($value): float
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new ValueMust('be parsable as float');
        }

        return (float) $value;
    }
}
