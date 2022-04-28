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
        parent::__construct(
            $exceptionFactory,
            $ruleChain,
            $validated,
            $valueName,
            /** @phpstan-ignore-next-line */
            new Key(
                $exceptionFactory,
                /** @phpstan-ignore-next-line */
                $ruleChain,
                $validated,
                $valueName,
                $componentName
            )
        );
    }

    /**
     * @template Tstring of non-empty-string
     * @param non-empty-array<Tstring> $values
     * @return InRule<Tstring>
     */
    public function in(array $values): InRule
    {
        /** @var InRule<Tstring> $inRule */
        $inRule = new InRule(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new ComparedValue(),
            $values
        );
        return $inRule;
    }
}
