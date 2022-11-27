<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @template Tstring of string
 * @extends ReadableRule<Tstring, Tstring>
 */
final class Regex extends ReadableRule
{
    private string $pattern;
    /** @var PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 */
    private int $flags;
    /** @var array<string> */
    private array $matches;

    /**
     * @param RuleChain<Tstring> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $pattern
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $pattern, int $flags = 0)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->pattern = $pattern;
        $this->flags = $flags;
        $this->matches = [];
    }

    /**
     * @deprecated will be removed use: ParseRegex::match (Validator::make()->string()->parseRegex()->match())
     */
    public function match(string $matchKey): RegexMatch
    {
        $this->validateChain();
        /** @var Validated<mixed> $validated */
        $validated = new Validated($this->matches);
        return new RegexMatch($this->exceptionFactory(), new RuleChain(), $validated, $this->valueName().' regex: \''.$this->pattern.'\' match: \''.$matchKey.'\'', $matchKey);
    }

    /**
     * @param Tstring $value
     * @return Tstring
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
