<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Factory\Exception;
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
    /** @var non-empty-string */
    private string $format;
    /** @var class-string<TDateTime>  */
    private string $dateTimeClass;

    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $format
     * @param class-string<TDateTime> $dateTimeClass
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $format, string $dateTimeClass)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->format = $format;
        $this->dateTimeClass = $dateTimeClass;
    }

    /**
     * @param TDateTime $min
     * @return MinWithMax<TDateTime, TDateTime>
     */
    public function min(\DateTimeInterface $min): MinWithMax
    {
        /** @var MinWithMax<TDateTime, TDateTime> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
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
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
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
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
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
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
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
        $dateTime = \DateTimeImmutable::createFromFormat($this->format, $value);
        if ($dateTime === false) {
            throw new ValueMust('be date time in format: "'.$this->format.'"');
        }

        return new $this->dateTimeClass($value);
    }
}
