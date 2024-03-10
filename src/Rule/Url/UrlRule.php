<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Conversion;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\String\StringLength;

/**
 * @template Tstring of string
 * @extends Rule<Tstring, Tstring>
 */
final class UrlRule extends Rule
{
    /** @var ParseUrl<Tstring> */
    private readonly ParseUrl $parseUrl;

    /**
     * @param RuleChain<Tstring> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @param array<non-empty-string> $requiredSchemes
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        array $requiredComponents,
        array $forbiddenComponents,
        private readonly array $requiredSchemes
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $this->parseUrl = new ParseUrl(null, new RuleChain(), $this->validated, $this->valueName, $requiredComponents, $forbiddenComponents);
    }

    /**
     * @param positive-int $max
     * @return Max<Tstring, int>
     */
    public function max(int $max): Max
    {
        /** @var RuleChain<string> $ruleChain */
        $ruleChain = $this->ruleChain();
        /** @var Conversion<string, float|int|string|\Stringable> $stringLength */
        $stringLength = new StringLength();
        /** @var Max<Tstring, int> */
        return new Max(
            $this->exceptionFactory,
            $ruleChain,
            $this->validated,
            $this->valueName,
            $stringLength,
            new CastString(),
            $max,
            'url length'
        );
    }

    /**
     * @param Tstring $value
     * @return Tstring
     */
    protected function validate($value): string
    {
        $components = $this->parseUrl->validate($value);
        if (count($this->requiredSchemes) !== 0 && !in_array($components['scheme'] ?? null, $this->requiredSchemes, true)) {
            throw new ValueMust('contains one of url schemes: '.implode(', ', $this->requiredSchemes));
        }
        return $value;
    }
}
