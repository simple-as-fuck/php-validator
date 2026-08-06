<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

final class ArrayRule extends \SimpleAsFuck\Validator\Rule\Common\ArrayRule
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable'): \SimpleAsFuck\Validator\Rule\Common\ArrayRule
    {
        return new ArrayRule(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($value),
            $valueName,
        );
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        if (!is_array($value)) {
            throw new ValueMust('be array, '.gettype($value).' given');
        }

        return $value;
    }
}
