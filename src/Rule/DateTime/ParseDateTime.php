<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TDateTime of \DateTimeInterface
 * @extends ReadableRule<string, TDateTime>
 */
final class ParseDateTime extends ReadableRule
{
    private string $format;
    /** @var class-string<TDateTime>  */
    private string $dateTimeClass;

    /**
     * @param Rule<mixed, string> $rule
     * @param class-string<TDateTime> $dateTimeClass
     */
    public function __construct(Rule $rule, string $valueName, string $format, string $dateTimeClass)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $valueName);

        $this->format = $format;
        $this->dateTimeClass = $dateTimeClass;
    }

    /**
     * @param TDateTime $min
     * @return MinWithMax<TDateTime, TDateTime>
     */
    public function min(\DateTimeInterface $min): MinWithMax
    {
        /** @phpstan-ignore-next-line */
        return new MinWithMax($this, $this->valueName(), new ComparedValue(), new ToIsoString(), $min);
    }

    /**
     * @return MinWithMax<TDateTime, TDateTime>
     */
    public function future(): MinWithMax
    {
        /** @phpstan-ignore-next-line */
        return new MinWithMax($this, $this->valueName(), new ComparedValue(), new ToIsoString(), new \DateTimeImmutable());
    }

    /**
     * @return Past<TDateTime>
     */
    public function past(): Past
    {
        /** @phpstan-ignore-next-line */
        return new Past($this, $this->valueName(), new ComparedValue(), new ToIsoString(), new \DateTimeImmutable());
    }

    /**
     * @param TDateTime $max
     * @return Max<TDateTime, TDateTime>
     */
    public function max(\DateTimeInterface $max): Max
    {
        /** @phpstan-ignore-next-line */
        return new Max($this, $this->valueName(), new ComparedValue(), new ToIsoString(), $max);
    }

    /**
     * @param string $value
     * @return TDateTime
     */
    protected function validate($value): \DateTimeInterface
    {
        $dateTime = \DateTimeImmutable::createFromFormat($this->format, $value);
        if ($dateTime === false) {
            throw new ValueMust('be date time in format: "'.$this->format.'"');
        }

        return new $this->dateTimeClass($value);
    }
}
