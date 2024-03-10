<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<mixed, bool>
 */
final class BoolRule extends Rule
{
    /**
     * @param mixed $value
     */
    protected function validate($value): bool
    {
        if (!is_bool($value)) {
            throw new ValueMust('be boolean, '.gettype($value).' given');
        }

        return $value;
    }
}
