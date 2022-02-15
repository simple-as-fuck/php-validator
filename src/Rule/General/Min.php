<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @template TCompared
 * @extends Comparison<TValue, TCompared>
 */
class Min extends Comparison
{
    /** @var ToString<TCompared> */
    private ToString $toString;
    /** @var non-empty-string */
    private string $comparedName;

    /**
     * @param Rule<TValue, TValue> $rule
     * @param Compared<TValue, TCompared> $compared
     * @param ToString<TCompared> $toString
     * @param non-empty-string $comparedName
     */
    public function __construct(Rule $rule, string $valueName, Compared $compared, ToString $toString, $comparedTo, string $comparedName = 'value')
    {
        parent::__construct($rule, $valueName, $compared, $comparedTo);
        $this->toString = $toString;
        $this->comparedName = $comparedName;
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
        if ($compared < $comparedTo) {
            throw new ValueMust('have minimum '.$this->comparedName.': '.$this->toString->convert($comparedTo));
        }
    }
}
