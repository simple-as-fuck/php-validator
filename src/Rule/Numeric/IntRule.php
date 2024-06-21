<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends \SimpleAsFuck\Validator\Rule\General\IntRule<mixed>
 */
final class IntRule extends \SimpleAsFuck\Validator\Rule\General\IntRule
{
    /**
     * @param non-empty-string $valueName
     * @return \SimpleAsFuck\Validator\Rule\General\IntRule<mixed>
     */
    public static function make(mixed $value, string $valueName = 'variable'): \SimpleAsFuck\Validator\Rule\General\IntRule
    {
        return new IntRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName);
    }

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
