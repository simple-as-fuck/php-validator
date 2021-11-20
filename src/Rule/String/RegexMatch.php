<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<array<string>, string>
 */
final class RegexMatch extends ReadableRule
{
    /** @var Key<string> */
    private Key $keyRule;

    /**
     * @param RuleChain<array<string>> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $key)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->keyRule = new Key($exceptionFactory, $ruleChain, $validated, $valueName, $key);
    }

    public function parseInt(): ParseInt
    {
        return new ParseInt($this, $this->valueName());
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this, $this->valueName());
    }

    protected function validate($value)
    {
        return $this->keyRule->validate($value);
    }
}
