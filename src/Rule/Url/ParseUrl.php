<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\String\StringLength;

/**
 * @template TString of string
 * @extends ReadableRule<string, TString>
 */
class ParseUrl extends ReadableRule
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
    /** @var array<literal-string&non-empty-string, int|string> */
    private array $urlComponents;

    /**
     * @template MakeTString of string
     * @param MakeTString $value
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<0,7>> $forbiddenComponents array of PHP_URL_ constants
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
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<0,7>> $forbiddenComponents array of PHP_URL_ constants
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, array $requiredComponents, array $forbiddenComponents)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        $mapComponent = fn (int $component): string => self::COMPONENT_KEY_MAP[$component];
        $this->requiredComponents = array_map($mapComponent, $requiredComponents);
        $this->forbiddenComponents = array_map($mapComponent, $forbiddenComponents);
        $this->urlComponents = [];
    }

    /**
     * @param positive-int $max
     * @return Max<TString, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<TString, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new StringLength(),
            new CastString(),
            $max,
            'url length'
        );
        return $maxRule;
    }

    public function scheme(): Scheme
    {
        return new Scheme(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url scheme',
            'scheme',
            true
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function host(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url host',
            'host',
            true
        );
    }

    /**
     * @return Component<positive-int>
     */
    public function port(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url port',
            'port',
            true
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function user(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url user',
            'user',
            true
        );
    }

    /**
     * @return Component<non-empty-string>
     */
    public function pass(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url pass',
            'pass',
            true
        );
    }

    /**
     * @return Component<string>
     */
    public function path(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url path',
            'path',
            true
        );
    }

    public function query(): Query
    {
        return new Query(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url query',
            'query',
            true
        );
    }

    /**
     * @return Component<string>
     */
    public function fragment(): Component
    {
        return new Component(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' url fragment',
            'fragment',
            true
        );
    }

    /**
     * @return array<literal-string&non-empty-string, int|string>
     */
    final protected function urlComponents(): array
    {
        return $this->urlComponents;
    }

    /**
     * @param string $value
     * @return TString
     */
    protected function validate($value)
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

        $this->urlComponents = $components;

        /** @var TString */
        return $value;
    }

    /**
     * @return Validated<mixed>
     */
    protected function secondaryOutput(): Validated
    {
        /** @var Validated<mixed> */
        return new Validated($this->urlComponents);
    }
}
