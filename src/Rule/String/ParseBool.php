<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, bool>
 */
final class ParseBool extends ReadableRule
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $trueDefinition
     * @param non-empty-string $falseDefinition
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly string $trueDefinition,
        private readonly string $falseDefinition,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param string $value
     */
    protected function validate($value): bool
    {
        return match ($value) {
            $this->trueDefinition => true,
            $this->falseDefinition => false,
            default => throw new ValueMust('be parsable as bool (\'' . $this->trueDefinition . '\' mean true, \'' . $this->falseDefinition . '\' mean false)')
        };
    }
}
