<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @template TCompared
 * @extends Comparison<TValue, TCompared>
 */
final class Min extends Comparison
{
    /**
     * @param TCompared $maxValue
     * @return Max<TValue, TCompared>
     */
    public function max($maxValue): Max
    {
        if ($this->comparedTo() >= $maxValue) {
            throw new \LogicException('Max value rule parameter must by greater than min value');
        }

        return new Max($this, $this->valueName(), $this->compared(), $maxValue);
    }

    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($compared < $comparedTo) {
            throw new ValueMust('be a minimum: '.$comparedTo);
        }
    }
}
