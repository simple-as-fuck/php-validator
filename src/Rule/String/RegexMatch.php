<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;

/**
 * @extends \SimpleAsFuck\Validator\Rule\Common\StringRule<array<string>>
 */
final class RegexMatch extends \SimpleAsFuck\Validator\Rule\Common\StringRule
{
    /** @var Key<string> */
    private readonly Key $key;

    /**
     * @param RuleChain<covariant array<string>> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $key)
    {
        $key = new Key($exceptionFactory, $ruleChain, $validated, $valueName, $key);
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->key = $key;
    }

    /**
     * @param array<string> $value
     */
    protected function validate($value): ?string
    {
        return $this->key->validate($value);
    }

    /**
     * @return non-empty-string
     */
    protected function valueName(): string
    {
        return $this->valueName;
    }
}
