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
 * @extends Rule<TValue, TValue>
 */
abstract class Comparison extends Rule
{
    /**
     * @param RuleChain<covariant TValue> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, covariant TCompared> $conversion
     * @param TCompared $comparedTo
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        protected readonly Conversion $conversion,
        protected readonly mixed $comparedTo
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param TValue $value
     * @return TValue
     */
    final protected function validate($value)
    {
        $this->compare($this->conversion->convert($value), $this->comparedTo);
        return $value;
    }

    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     * @throws ValueMust
     */
    abstract protected function compare($compared, $comparedTo): void;
}
