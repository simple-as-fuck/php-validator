<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

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
     * @param Rule<TValue, TValue> $rule
     * @param Compared<TValue, TCompared> $compared
     * @param TCompared $comparedTo
     */
    public function __construct(Rule $rule, string $valueName, Compared $compared, $comparedTo)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $valueName);
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
