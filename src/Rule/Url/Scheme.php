<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\ForwardRule;
use SimpleAsFuck\Validator\Rule\General\InRule;

/**
 * @extends ForwardRule<array<non-empty-string>, non-empty-string>
 */
final class Scheme extends ForwardRule
{
    /**
     * @param RuleChain<array<non-empty-string>> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $componentName)
    {
        /** @phpstan-ignore-next-line */
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, new Key($exceptionFactory, $ruleChain, $validated, $valueName, $componentName));
    }

    /**
     * @param non-empty-array<non-empty-string> $values
     * @return InRule<non-empty-string>
     */
    public function in(array $values): InRule
    {
        return new InRule($this, $this->valueName(), new ComparedValue(), $values);
    }
}
