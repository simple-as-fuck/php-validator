<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends Numeric<string>
 */
final class ParseNumeric extends Numeric
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly bool $allowLeadingZero = false,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    public function unsigned(): UnsignedNumeric
    {
        return new UnsignedNumeric($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @return Numeric<numeric-string>
     */
    public function positive(): Numeric
    {
        return $this->unsigned()->nonZero();
    }

    /**
     * @param string $value
     * @return numeric-string
     */
    protected function validate($value): string
    {
        if (preg_match('/^-?(0|['.($this->allowLeadingZero ? 0 : 1).'-9]\d*)(\.\d+)?$/', $value) !== 1) {
            throw new ValueMust('be parsable as number in decimal system'.($this->allowLeadingZero ? ' (leading zero allowed)' : ''));
        }

        /** @var numeric-string $value */
        return $value;
    }
}
