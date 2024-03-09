<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;

/**
 * @template TValue
 * @template TCompared
 * @extends ReadableRule<TValue, TValue>
 */
abstract class Comparison extends ReadableRule
{
    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param Conversion<TValue, TCompared> $conversion
     * @param TCompared $comparedTo
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly Conversion $conversion,
        private readonly mixed $comparedTo
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
     */
    abstract protected function compare($compared, $comparedTo): void;

    /**
     * @return Conversion<TValue, TCompared>
     */
    protected function conversion(): Conversion
    {
        return $this->conversion;
    }

    /**
     * @return TCompared
     */
    protected function comparedTo()
    {
        return $this->comparedTo;
    }
}
