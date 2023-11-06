<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends \SimpleAsFuck\Validator\Rule\General\FloatRule<mixed>
 */
final class FloatRule extends \SimpleAsFuck\Validator\Rule\General\FloatRule
{
    /**
     * @param mixed $value
     */
    protected function validate($value): float
    {
        if (!is_int($value) && !is_float($value)) {
            throw new ValueMust('be float, '.gettype($value).' given');
        }

        return $value;
    }
}
