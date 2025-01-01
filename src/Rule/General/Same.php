<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @extends Comparison<TValue, int>
 */
final class Same extends Comparison
{
    /**
     * @param RuleChain<covariant TValue> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, covariant int> $conversion
     * @param non-empty-string $comparedName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        Conversion $conversion,
        int $comparedTo,
        private readonly string $comparedName = 'value'
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $conversion, $comparedTo);
    }

    /**
     * @param int $compared
     * @param int $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($compared !== $comparedTo) {
            throw new ValueMust('have '.$this->comparedName.': '.$comparedTo);
        }
    }
}
