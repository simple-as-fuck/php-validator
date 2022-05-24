<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TValue
 * @extends ReadableRule<TValue, TValue>
 */
class InRule extends ReadableRule
{
    /** @var non-empty-array<TValue> */
    private array $values;

    /**
     * @param RuleChain<TValue> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-array<TValue> $values
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, array $values)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->values = $values;
    }

    /**
     * @param TValue $value
     * @return TValue
     */
    protected function validate($value)
    {
        if (! in_array($value, $this->values, true)) {
            throw new ValueMust('be in values list: '.implode(', ', $this->values));
        }

        return $value;
    }
}
