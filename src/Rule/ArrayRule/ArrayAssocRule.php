<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Model\ValueMust;

final class ArrayAssocRule extends \SimpleAsFuck\Validator\Rule\Common\ArrayRule
{
    /**
     * @param mixed $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_object($value)) {
            return \get_object_vars($value);
        }

        throw new ValueMust('be object or array, '.gettype($value).' given');
    }
}
