<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<mixed, float>
 */
final class FloatRule extends ReadableRule
{
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
     * @param mixed $value
     */
    protected function validate($value): float
    {
        if (! is_int($value) && ! is_float($value)) {
            throw new ValueMust('be float');
        }

        return $value;
    }
}
