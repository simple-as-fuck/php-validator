<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @extends Comparison<TValue, array<TValue>>
 */
class InRule extends Comparison
{
    /**
     * @param TValue $compared
     * @param array<TValue> $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if (! in_array($compared, $comparedTo, true)) {
            throw new ValueMust('be in values list: '.implode(', ', $comparedTo));
        }
    }
}
