<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template Tout
 * @extends Rule<object, array<Tout>>
 */
final class Properties extends Rule
{
    /**
     * @param RuleChain<covariant object> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param callable(Property): Tout $callable
     */
    public function __construct(
        Exception $exceptionFactory,
        private readonly RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly mixed $callable,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param object $value
     * @return array<Tout>
     */
    protected function validate($value): array
    {
        $value = \get_object_vars($value);
        $result = [];
        foreach ($value as $propertyName => $property) {
            $result[$propertyName] = ($this->callable)(new Property(
                $this->exceptionFactory,
                $this->ruleChain,
                $this->validated,
                $this->valueName,
                $propertyName,
            ));
        }
        return $result;
    }
}
