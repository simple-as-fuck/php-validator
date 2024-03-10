<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<array{scheme?: string, host?: string, user?: string, pass?: string, path?: string, query?: string, fragment?: string}, string>
 */
final class Component extends ReadableRule
{
    /** @var Key<string> */
    private readonly Key $key;

    /**
     * @param RuleChain<covariant array{scheme?: string, host?: string, user?: string, pass?: string, path?: string, query?: string, fragment?: string}> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param 'scheme'|'host'|'user'|'pass'|'path'|'query'|'fragment' $componentName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        string $componentName
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->key = new Key($exceptionFactory, $ruleChain, $validated, $valueName, $componentName);
    }

    /**
     * @param array<non-empty-string, string> $value
     */
    protected function validate($value): ?string
    {
        return $this->key->validate($value);
    }
}
