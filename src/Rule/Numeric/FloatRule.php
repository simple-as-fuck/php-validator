<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<mixed, float>
 */
final class FloatRule extends ReadableRule
{
    /**
     * @return Min<float, float>
     */
    public function min(float $min): Min
    {
        return new Min($this, $this->valueName(), new ComparedValue(), $min);
    }

    /**
     * @return Max<float, float>
     */
    public function max(float $max): Max
    {
        return new Max($this, $this->valueName(), new ComparedValue(), $max);
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
