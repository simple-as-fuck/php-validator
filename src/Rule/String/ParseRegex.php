<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<string, array<string>>
 */
final class ParseRegex extends Rule
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $pattern
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private string $pattern,
        private int $flags
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param non-empty-string $matchKey
     */
    public function match(string $matchKey): RegexMatch
    {
        return new RegexMatch($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().' regex: \''.$this->pattern.'\' match: \''.$matchKey.'\'', $matchKey);
    }

    /**
     * @param string $value
     * @return array<string>
     */
    protected function validate($value): array
    {
        $result = preg_match($this->pattern, $value, $matches, $this->flags);
        if ($result === 0) {
            throw new ValueMust('match regex: \''.$this->pattern.'\'');
        }
        if ($result === false) {
            throw new \RuntimeException('Regex failed with pater: \''.$this->pattern.'\'');
        }

        /** @var array<string> */
        return $matches;
    }
}
