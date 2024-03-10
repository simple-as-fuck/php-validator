<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\Custom\UserArrayRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TArrayRule of object
 * @template TClass of object
 * @extends Rule<array<mixed>, TClass>
 */
final class ClassFromArray extends Rule
{
    /**
     * @param TArrayRule $arrayRule
     * @param UserArrayRule<TArrayRule, TClass> $userArrayRule
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly object $arrayRule,
        private readonly UserArrayRule $userArrayRule
    ) {
        parent::__construct(
            $exceptionFactory,
            $ruleChain,
            $validated,
            $valueName
        );
    }

    /**
     * @param array<mixed> $value
     * @return TClass|null
     */
    protected function validate($value): ?object
    {
        return $this->userArrayRule->validate($this->arrayRule);
    }
}
