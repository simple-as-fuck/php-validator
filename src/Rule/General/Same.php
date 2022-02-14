<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

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
     * @param Rule<TValue, TValue> $rule
     * @param Compared<TValue, TCompared> $compared
     * @param TCompared $comparedTo
     * @param non-empty-string $comparedName
     */
    public function __construct(Rule $rule, string $valueName, Compared $compared, $comparedTo, string $comparedName = 'value')
    {
        parent::__construct($rule, $valueName, $compared, $comparedTo);
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
