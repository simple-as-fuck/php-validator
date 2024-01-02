<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\InRule;

/**
 * @template Tout of string
 * @extends InRule<string, Tout>
 * /
 */
final class CaseInsensitiveInRule extends InRule
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-array<Tout> $values
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly array $values
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $values);
    }

    /**
     * @param string $value
     * @return Tout
     */
    protected function validate($value): string
    {
        $value = strtolower($value);

        foreach ($this->values as $validValue) {
            if ($value === strtolower($validValue)) {
                return $validValue;
            }
        }

        throw new ValueMust('be in values list: '.implode(', ', $this->values));
    }
}
