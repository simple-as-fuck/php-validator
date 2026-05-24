<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
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
     * @param non-empty-string $pattern cool example: '/(?P<matchKey>.*)/'
     * @param string $value
     * @param int-mask-of<PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL> $flags
     * @param non-empty-string $valueName
     */
    public static function make(
        string $pattern,
        string $value,
        int $flags = 0,
        string $valueName = 'variable',
    ): ParseRegex {
        /** @var RuleChain<string> $ruleChain */
        $ruleChain = new RuleChain();
        return new ParseRegex(new UnexpectedValueException(), $ruleChain, new Validated($value), $valueName, $pattern, $flags);
    }

    /**
     * @param RuleChain<covariant string> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $pattern
     * @param int-mask-of<PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL> $flags
     */
    public function __construct(
        Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly string $pattern,
        private readonly int $flags,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param non-empty-string $matchKey
     */
    public function match(string $matchKey): RegexMatch
    {
        return new RegexMatch($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' regex: \''.$this->pattern.'\' match: \''.$matchKey.'\'', $matchKey);
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
