<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @extends Rule<array<mixed>, mixed>
 */
final class StringTypedKey extends Rule
{
    /**
     * @param RuleChain<array<mixed>> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param Key<mixed> $keyRule
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly Key $keyRule
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    public function string(): StringRule
    {
        return new StringRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function array(): ArrayOfString
    {
        return new ArrayOfString($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @param array<mixed> $value
     * @return mixed|null
     */
    protected function validate($value)
    {
        return $this->keyRule->validate($value);
    }
}
