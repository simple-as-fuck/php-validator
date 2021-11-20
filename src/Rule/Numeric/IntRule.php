<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<mixed, int>
 */
final class IntRule extends ReadableRule
{
    /**
     * @return Min<int, int>
     */
    public function min(int $min): Min
    {
        return new Min($this, $this->valueName(), new ComparedValue(), $min);
    }

    /**
     * @return Max<int, int>
     */
    public function max(int $max): Max
    {
        return new Max($this, $this->valueName(), new ComparedValue(), $max);
    }

    /**
     * @param non-empty-array<int> $values
     * @return InRule<int>
     */
    public function in(array $values): InRule
    {
        return new InRule($this, $this->valueName(), new ComparedValue(), $values);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): int
    {
        if (! is_int($value)) {
            throw new ValueMust('be integer');
        }

        return $value;
    }
}
