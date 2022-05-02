<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, string>
 */
final class Regex extends ReadableRule
{
    private string $pattern;
    private int $flags;
    /** @var array<string> */
    private array $matches;

    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $pattern, int $flags = 0)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->pattern = $pattern;
        $this->flags = $flags;
        $this->matches = [];
    }

    public function match(string $matchKey): RegexMatch
    {
        $this->validateChain();
        /** @var Validated<mixed> $validated */
        $validated = new Validated($this->matches);
        return new RegexMatch($this->exceptionFactory(), new RuleChain(), $validated, $this->valueName().' regex: \''.$this->pattern.'\' match: \''.$matchKey.'\'', $matchKey);
    }

    /**
     * @param string $value
     */
    protected function validate($value): string
    {
        $result = preg_match($this->pattern, $value, $this->matches, $this->flags);
        if ($result === 0) {
            throw new ValueMust('match regex: \''.$this->pattern.'\'');
        }
        if ($result === false) {
            throw new \RuntimeException('Regex failed with pater: \''.$this->pattern.'\'');
        }

        return $value;
    }
}
