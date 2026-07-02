<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Model\ValueMust;

final class ArrayRule extends \SimpleAsFuck\Validator\Rule\Common\ArrayRule
{
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
