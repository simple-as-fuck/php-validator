<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\NoConversion;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @template TDateTime of \DateTimeInterface
 * @extends ReadableRule<string, TDateTime>
 */
final class ParseDateTime extends ReadableRule
{
    private readonly ?\DateTimeZone $timeZone;

    /**
     * @template MakeTDateTime of \DateTimeInterface
     * @param non-empty-string $format
     * @param class-string<MakeTDateTime> $dateTimeClass
     * @param non-empty-string $valueName
     * @param non-empty-string|null $timeZone
     * @return ParseDateTime<MakeTDateTime>
     */
    public static function make(?string $value, string $format, string $dateTimeClass, string $valueName = 'variable', ?string $timeZone = null): ParseDateTime
    {
        /** @var mixed $value */
        return new ParseDateTime(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName, $format, $dateTimeClass, $timeZone);
    }

    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $format
     * @param class-string<TDateTime> $dateTimeClass
     * @param non-empty-string|null $timeZone
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly string $format,
        private readonly string $dateTimeClass,
        ?string $timeZone = null
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->timeZone = $timeZone !== null ? new \DateTimeZone($timeZone) : null;
    }

    /**
     * @param TDateTime $min
     * @return MinWithMax<TDateTime, TDateTime>
     */
    public function min(\DateTimeInterface $min): MinWithMax
    {
        /** @var MinWithMax<TDateTime, TDateTime> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new ToIsoString(),
            $min
        );
        return $minRule;
    }

    /**
     * @return MinWithMax<TDateTime, TDateTime>
     */
    public function future(): MinWithMax
    {
        /** @var MinWithMax<TDateTime, TDateTime> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new ToIsoString(),
            new \DateTimeImmutable()
        );
        return $minRule;
    }

    /**
     * @return Past<TDateTime>
     */
    public function past(): Past
    {
        /** @var Past<TDateTime> $pastRule */
        $pastRule = new Past(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new ToIsoString(),
            new \DateTimeImmutable()
        );
        return $pastRule;
    }

    /**
     * @param TDateTime $max
     * @return Max<TDateTime, TDateTime>
     */
    public function max(\DateTimeInterface $max): Max
    {
        /** @var Max<TDateTime, TDateTime> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new NoConversion(),
            /** @phpstan-ignore-next-line */
            new ToIsoString(),
            $max
        );
        return $maxRule;
    }

    /**
     * @param string $value
     * @return TDateTime
     */
    protected function validate($value): \DateTimeInterface
    {
        $dateTime = \DateTime::createFromFormat($this->format, $value, $this->timeZone);
        if ($dateTime === false) {
            throw new ValueMust('be date time in format: \''.$this->format.'\' example: \''.(new \DateTimeImmutable('now', $this->timeZone))->format($this->format).'\'');
        }

        if ($this->timeZone !== null) {
            $dateTime->setTimezone($this->timeZone);
        }

        return new $this->dateTimeClass(
            $dateTime->format('Y-m-d H:i:s.u'),
            $dateTime->getTimezone()
        );
    }
}
