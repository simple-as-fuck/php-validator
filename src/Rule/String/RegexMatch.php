<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\General\ForwardRule;

/**
 * @extends ForwardRule<array<string>, string>
 * @phpstan-ignore-next-line
 */
final class RegexMatch extends ForwardRule
{
    /** @var Key<string> */
    private readonly Key $key;

    /**
     * @param RuleChain<covariant array<string>> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $key)
    {
        $key = new Key($exceptionFactory, $ruleChain, $validated, $valueName, $key);
        /** @phpstan-ignore-next-line */
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $key);
        $this->key = $key;
    }

    public function parseInt(): ParseInt
    {
        return new ParseInt($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @template TDateTime of \DateTimeInterface
     * @param non-empty-string $format
     * @param class-string<TDateTime> $dateTimeClass
     * @param non-empty-string|null $timeZone
     * @param bool $strictTimeZone true will force timezone name, to prevent timezone conversion
     * @return ParseDateTime<TDateTime>
     */
    public function parseDateTime(
        string $format,
        string $dateTimeClass = \DateTimeImmutable::class,
        ?string $timeZone = null,
        bool $strictTimeZone = false,
    ): ParseDateTime {
        return new ParseDateTime(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $format,
            $dateTimeClass,
            $timeZone,
            $strictTimeZone,
        );
    }

    public function notEmpty(bool $emptyAsNull = false): NotEmpty
    {
        return new NotEmpty($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $emptyAsNull);
    }

    /**
     * @param array<string> $value
     */
    protected function validate($value): ?string
    {
        return $this->key->validate($value);
    }
}
