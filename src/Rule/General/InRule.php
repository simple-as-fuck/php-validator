<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template Tin
 * @template Tout
 * @extends ReadableRule<Tin, Tout>
 */
class InRule extends ReadableRule
{
    /** @var non-empty-array<Tout> */
    private array $values;

    /**
     * @param RuleChain<Tin> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-array<Tout> $values
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, array $values)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->values = $values;
    }

    /**
     * @param Tin $value
     * @return Tout
     */
    protected function validate($value)
    {
        if (!in_array($value, $this->values, true)) {
            throw new ValueMust('be in values list: '.implode(', ', $this->values));
        }

        return $value;
    }
}
