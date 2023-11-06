<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends \SimpleAsFuck\Validator\Rule\General\IntRule<mixed>
 */
final class IntRule extends \SimpleAsFuck\Validator\Rule\General\IntRule
{
    /**
     * @param mixed $value
     */
    protected function validate($value): int
    {
        if (!is_int($value)) {
            throw new ValueMust('be integer, '.gettype($value).' given');
        }

        return $value;
    }
}
