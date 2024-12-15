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
final class Same extends Comparison
{
    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, TCompared> $conversion
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        Conversion $conversion,
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
        if ($compared !== $comparedTo) {
            /** todo remove is_ and use like string types in template */
            if (is_string($comparedTo) || is_int($comparedTo) || is_float($comparedTo)) {
                throw new ValueMust('have '.$this->comparedName.': '.$comparedTo);
            }

            throw new ValueMust('have '.$this->comparedName.': correct value');
        }
    }
}
