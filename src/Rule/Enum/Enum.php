<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Enum;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TEnum of \BackedEnum
 * @extends Rule<int|string, TEnum>
 */
final class Enum extends Rule
{
    /**
     * @param RuleChain<covariant int|string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param class-string<TEnum> $enumClass
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        readonly private string $enumClass,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param int|string $value
     * @return TEnum
     */
    protected function validate($value): \BackedEnum
    {
        $enum = $this->enumClass::tryFrom($value);
        if ($enum === null) {
            $values = array_map(static fn (\BackedEnum $case): int|string => $case->value, $this->enumClass::cases());
            throw new ValueMust('be in values list: '.implode(', ', $values));
        }

        return $enum;
    }
}
