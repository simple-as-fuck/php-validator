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
class Min extends Comparison
{
    /** @var Conversion<TCompared, string> */
    private Conversion $toString;
    /** @var non-empty-string */
    private string $comparedName;

    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, TCompared> $conversion
     * @param Conversion<TCompared, string> $toString
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        Conversion $conversion,
        Conversion $toString,
        $comparedTo,
        string $comparedName = 'value'
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $conversion, $comparedTo);
        $this->toString = $toString;
        $this->comparedName = $comparedName;
    }

    /**
     * @return Conversion<TCompared, string>
     */
    final protected function toString(): Conversion
    {
        return $this->toString;
    }

    /**
     * @return non-empty-string
     */
    final protected function comparedName(): string
    {
        return $this->comparedName;
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
