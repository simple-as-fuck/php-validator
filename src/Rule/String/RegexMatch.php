<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ForwardRule;

/**
 * @extends ForwardRule<array<string>, string>
 */
final class RegexMatch extends ForwardRule
{
    /**
     * @param RuleChain<array<string>> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $key)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, new Key($exceptionFactory, $ruleChain, $validated, $valueName, $key));
    }

    public function parseInt(): ParseInt
    {
        return new ParseInt($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }
}
