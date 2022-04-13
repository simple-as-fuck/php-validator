<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\IntRule;

/**
 * @extends IntRule<string>
 */
final class ParseInt extends IntRule
{
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
