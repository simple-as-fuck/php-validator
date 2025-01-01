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
    /**
     * @param RuleChain<covariant TValue> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, covariant TCompared> $conversion
     * @param Conversion<TCompared, covariant string> $toString
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        Conversion $conversion,
        protected readonly Conversion $toString,
        $comparedTo,
        private readonly string $comparedName = 'value'
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $conversion, $comparedTo);
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
