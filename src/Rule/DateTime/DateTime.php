<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<string, non-empty-string>
 */
final class DateTime extends Rule
{
    private readonly ?\DateTimeZone $timeZone;

    /**
     * @param RuleChain<covariant string> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-string $format
     * @param non-empty-string|null $timeZone
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly string $format,
        ?string $timeZone = null,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->timeZone = $timeZone !== null ? new \DateTimeZone($timeZone) : null;
    }

    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        $dateTime = \DateTime::createFromFormat($this->format, $value, $this->timeZone);
        if ($dateTime === false) {
            throw new ValueMust('be date time in format: \''.$this->format.'\' example: \''.(new \DateTimeImmutable('now', $this->timeZone))->format($this->format).'\'');
        }

        if ($this->timeZone !== null) {
            $dateTime->setTimezone($this->timeZone);
        }

        return $dateTime->format($this->format);
    }
}
