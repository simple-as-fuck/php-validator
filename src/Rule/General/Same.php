<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @template TCompared
 * @extends Comparison<TValue, TCompared>
 */
final class Same extends Comparison
{
    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($compared !== $comparedTo) {
            throw new ValueMust('be: '.$comparedTo);
        }
    }
}
