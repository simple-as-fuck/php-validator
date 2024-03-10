<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TClass of object
 * @extends Rule<object, TClass>
 */
final class CallableRule extends Rule
{
    /**
     * @param RuleChain<object> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param callable(ObjectRule): TClass $callable
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly ObjectRule $objectRule,
        private readonly mixed $callable,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param object $value
     * @return TClass
     */
    protected function validate($value): object
    {
        return ($this->callable)($this->objectRule);
    }
}
