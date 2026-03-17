<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template Tin
 * @todo 0.8 Tout use only stringable values
 * @template Tout
 * @extends Rule<Tin, Tout>
 */
class InRule extends Rule
{
    /**
     * @param RuleChain<covariant Tin> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-array<Tout> $values
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly array $values
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param Tin $value
     * @return Tout
     */
    protected function validate($value)
    {
        if (!in_array($value, $this->values, true)) {
            /** @todo 0.8 Tout will use only stringable values */
            throw new ValueMust('be in values list: '.implode(', ', array_filter($this->values, static fn ($v) => is_scalar($v))));
        }

        return $value;
    }
}
