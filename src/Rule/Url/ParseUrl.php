<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TString of string
 * @extends Rule<string, array<non-empty-string, string>>
 */
final class ParseUrl extends Rule
{
    private const COMPONENT_KEY_MAP = [
        PHP_URL_SCHEME => 'scheme',
        PHP_URL_HOST => 'host',
        PHP_URL_PORT => 'port',
        PHP_URL_USER => 'user',
        PHP_URL_PASS => 'pass',
        PHP_URL_PATH => 'path',
        PHP_URL_QUERY => 'query',
        PHP_URL_FRAGMENT => 'fragment',
    ];

    /** @var array<non-empty-string> */
    private array $requiredComponents;
    /** @var array<non-empty-string> */
    private array $forbiddenComponents;

    /**
     * @template MakeTString of string
     * @param MakeTString $value
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents array of PHP_URL_ constants
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents array of PHP_URL_ constants
     * @param non-empty-string $valueName
     * @return ParseUrl<MakeTString>
     */
    public static function make(string $value, array $requiredComponents = [], array $forbiddenComponents = [], string $valueName = 'variable'): ParseUrl
    {
        /** @var Validated<mixed> $validated */
        $validated = new Validated($value);
        /** @var ParseUrl<MakeTString> $parseUrl */
        $parseUrl = new ParseUrl(new UnexpectedValueException(), new RuleChain(), $validated, $valueName, $requiredComponents, $forbiddenComponents);
        return $parseUrl;
    }

    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents array of PHP_URL_ constants
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents array of PHP_URL_ constants
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, array $requiredComponents, array $forbiddenComponents)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $mapComponent = fn (int $component): string => self::COMPONENT_KEY_MAP[$component];
        $this->requiredComponents = array_map($mapComponent, $requiredComponents);
        $this->forbiddenComponents = array_map($mapComponent, $forbiddenComponents);
    }

    public function scheme(): Scheme
    {
        /** @var RuleChain<array<non-empty-string, non-empty-string>> $ruleChain */
        $ruleChain = $this->ruleChain();
        return new Scheme(
            $this->exceptionFactory(),
            $ruleChain,
            $this->validated(),
            $this->valueName().' url scheme',
            'scheme'
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function host(): Component
    {
        /** @var Component<non-empty-string> */
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url host',
            'host'
        );
    }

    /**
     * @return Component<positive-int>
     */
    public function port(): Component
    {
        /** @var Component<positive-int> */
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url port',
            'port'
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function user(): Component
    {
        /** @var Component<non-empty-string> */
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url user',
            'user'
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function pass(): Component
    {
        /** @var Component<non-empty-string> */
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url pass',
            'pass'
        );
    }

    /**
     * @return Component<string>
     */
    public function path(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url path',
            'path'
        );
    }

    /**
     * @return Component<string>
     */
    public function query(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url query',
            'query'
        );
    }

    public function parseQuery(): ParseQuery
    {
        return new ParseQuery($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().' url query');
    }

    /**
     * @return Component<string>
     */
    public function fragment(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url fragment',
            'fragment'
        );
    }

    /**
     * @param string $value
     * @return array<non-empty-string, string>
     */
    protected function validate($value): array
    {
        $components = parse_url($value);
        if ($components === false) {
            throw new ValueMust('be valid url');
        }

        foreach ($this->requiredComponents as $componentKey) {
            if (array_key_exists($componentKey, $components)) {
                continue;
            }

            throw new ValueMust('contains '.$componentKey.' url component');
        }

        foreach ($this->forbiddenComponents as $componentKey) {
            if (array_key_exists($componentKey, $components)) {
                throw new ValueMust('not contains '.$componentKey.' url component');
            }
        }

        /** @var array<non-empty-string, string> */
        return $components;
    }
}
