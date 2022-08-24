<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends ParseUrl<non-empty-string>
 */
final class ParseProtocolUrl extends ParseUrl
{
    /** @var non-empty-array<non-empty-string> */
    private array $requiredProtocols;

    /**
     * @param RuleChain<non-empty-string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<0,7>> $forbiddenComponents array of PHP_URL_ constants
     * @param non-empty-array<non-empty-string> $requiredProtocols
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        array $requiredComponents,
        array $forbiddenComponents,
        array $requiredProtocols
    ) {
        $requiredComponents[] = PHP_URL_SCHEME;
        $requiredComponents[] = PHP_URL_HOST;
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $requiredComponents, $forbiddenComponents);
        $this->requiredProtocols = $requiredProtocols;
    }

    /**
     * @param non-empty-string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        $value = parent::validate($value);
        if (! in_array($this->urlComponents()['scheme'], $this->requiredProtocols, true)) {
            throw new ValueMust('contains one of url schemes: '.implode(', ', $this->requiredProtocols));
        }

        return $value;
    }
}
