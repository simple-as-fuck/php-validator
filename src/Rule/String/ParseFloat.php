<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\FloatRule;

/**
 * @extends FloatRule<string>
 */
final class ParseFloat extends FloatRule
{
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
