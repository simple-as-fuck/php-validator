<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;

/**
 * @deprecated use CaseInsensitiveInRule
 * @template Tin of string
 * @template Tout of string
 * @extends \SimpleAsFuck\Validator\Rule\General\InRule<Tin, Tout>
 */
final class InRule extends \SimpleAsFuck\Validator\Rule\General\InRule
{
    private bool $ignoreCharacterSize;

    /**
     * @param RuleChain<Tin> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-array<Tout> $values
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        array $values,
        bool $ignoreCharacterSize
    ) {
        if ($ignoreCharacterSize) {
            /** @var non-empty-array<Tout> $values */
            $values = array_map(fn (string $value): string => strtolower($value), $values);
        }

        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $values);
        $this->ignoreCharacterSize = $ignoreCharacterSize;
    }

    /**
     * @param Tin $value
     * @return Tout
     */
    protected function validate($value): string
    {
        if ($this->ignoreCharacterSize) {
            /** @var Tin $value */
            $value = strtolower($value);
        }

        return parent::validate($value);
    }
}
