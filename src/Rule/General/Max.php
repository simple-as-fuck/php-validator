<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @template TCompared
 * @extends Comparison<TValue, TCompared>
 */
class Max extends Comparison
{
    /** @var ToString<TCompared> */
    private ToString $toString;

    /**
     * @param Rule<TValue, TValue> $rule
     * @param Compared<TValue, TCompared> $compared
     * @param ToString<TCompared> $toString
     * @param TCompared $comparedTo
     */
    public function __construct(Rule $rule, string $valueName, Compared $compared, ToString $toString, $comparedTo)
    {
        parent::__construct($rule, $valueName, $compared, $comparedTo);
        $this->toString = $toString;
    }

    /**
     * @return ToString<TCompared>
     */
    final protected function toString(): ToString
    {
        return $this->toString;
    }

    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($compared > $comparedTo) {
            throw new ValueMust('be a maximum: '.$this->toString->convert($comparedTo));
        }
    }
}
