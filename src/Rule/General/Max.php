<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
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
    /** @var non-empty-string */
    private string $comparedName;

    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param Compared<TValue, TCompared> $compared
     * @param ToString<TCompared> $toString
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, Compared $compared, ToString $toString, $comparedTo, string $comparedName = 'value')
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $compared, $comparedTo);
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
        if ($compared > $comparedTo) {
            throw new ValueMust('have maximum '.$this->comparedName.': '.$this->toString->convert($comparedTo));
        }
    }
}
