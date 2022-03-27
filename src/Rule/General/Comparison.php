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
    /** @var Compared<TValue, TCompared> */
    private Compared $compared;
    /** @var TCompared */
    private $comparedTo;

    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param Compared<TValue, TCompared> $compared
     * @param TCompared $comparedTo
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, Compared $compared, $comparedTo)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->compared = $compared;
        $this->comparedTo = $comparedTo;
    }

    /**
     * @param TValue $value
     * @return TValue
     */
    final protected function validate($value)
    {
        $this->compare($this->compared->convert($value), $this->comparedTo);
        return $value;
    }

    /**
     * @param TCompared $compared
     * @param TCompared $comparedTo
     */
    abstract protected function compare($compared, $comparedTo): void;

    /**
     * @return Compared<TValue, TCompared>
     */
    protected function compared(): Compared
    {
        return $this->compared;
    }

    /**
     * @return TCompared
     */
    protected function comparedTo()
    {
        return $this->comparedTo;
    }
}
