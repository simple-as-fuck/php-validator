<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\String\StringLength;

/**
 * @template TString of string
 * @extends ReadableRule<TString, TString>
 */
final class ParseUrl extends ReadableRule
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
        /** @phpstan-ignore-next-line */
        return new Max($this, $this->valueName().' url length', new StringLength(), new CastString(), $max);
    }

    public function scheme(): Scheme
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Scheme($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url scheme', 'scheme');
    }

    /**
     * @return Component<non-empty-string>
     */
    public function host(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url host', 'host');
    }

    /**
     * @return Component<positive-int>
     */
    public function port(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url port', 'port');
    }

    /**
     * @return Component<non-empty-string>
     */
    public function user(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url user', 'user');
    }

    /**
     * @return Component<non-empty-string>
     */
    public function pass(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url pass', 'pass');
    }

    /**
     * @return Component<string>
     */
    public function path(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url path', 'path');
    }

    /**
     * @return Component<string>
     */
    public function query(): Component
    {
        $this->validateChain();

        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url query', 'query');
    }

    /**
     * @return Component<string>
     */
    public function fragment(): Component
    {
        $this->validateChain();
        /** @phpstan-ignore-next-line */
        return new Component($this->exceptionFactory(), new RuleChain(), new Validated($this->urlComponents), $this->valueName().' url fragment', 'fragment');
    }

    /**
     * @param TString $value
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

        return $value;
    }
}
