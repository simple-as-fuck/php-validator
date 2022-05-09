<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;

/**
 * @template Tstring of string
 * @extends \SimpleAsFuck\Validator\Rule\General\InRule<Tstring>
 */
final class InRule extends \SimpleAsFuck\Validator\Rule\General\InRule
{
    private bool $ignoreCharacterSize;

    /**
     * @param RuleChain<Tstring> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-array<Tstring> $values
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
            /** @var non-empty-array<Tstring> $values */
            $values = array_map(fn (string $value): string => strtolower($value), $values);
        }

        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $values);
        $this->ignoreCharacterSize = $ignoreCharacterSize;
    }

    /**
     * @param Tstring $value
     * @return Tstring
     */
    protected function validate($value): string
    {
        if ($this->ignoreCharacterSize) {
            /** @var Tstring $value */
            $value = strtolower($value);
        }

        return parent::validate($value);
    }
}
