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
    /** @var non-empty-string */
    private string $comparedName;

    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param Conversion<TValue, TCompared> $conversion
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, Conversion $conversion, $comparedTo, string $comparedName = 'value')
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $conversion, $comparedTo);
        $this->comparedName = $comparedName;
    }

    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($compared !== $comparedTo) {
            throw new ValueMust('have '.$this->comparedName.': '.$comparedTo);
        }
    }
}
