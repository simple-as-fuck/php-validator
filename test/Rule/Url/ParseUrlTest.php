<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\Url\ParseUrl;

/**
 * @covers \SimpleAsFuck\Validator\Rule\Url\ParseUrl
 */
final class ParseUrlTest extends TestCase
{
    /**
     * @dataProvider dataProviderPort
     *
     * @param mixed $value
     */
    public function testPort(?int $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->port()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderPort(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, 'www.example.com', false],
            [null, null, 'www.example.com', true],
            [55, null, 'www.example.com:55', false],
            [55, null, 'www.example.com:55', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'value must be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderHost
     *
     * @param mixed $value
     */
    public function testHost(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->host()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderHost(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['www.example.com', null, 'https://www.example.com', false],
            ['www.example.com', null, 'https://www.example.com', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderPass
     *
     * @param mixed $value
     */
    public function testPass(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->pass()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderPass(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['pass', null, 'https://user:pass@www.example.com', false],
            ['pass', null, 'https://user:pass@www.example.com', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderFragment
     *
     * @param mixed $value
     */
    public function testFragment(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->fragment()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderFragment(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['fragment', null, '/www.example.com#fragment', false],
            ['fragment', null, '/www.example.com#fragment', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderUser
     *
     * @param mixed $value
     */
    public function testUser(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->user()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderUser(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['user', null, 'https://user:pass@www.example.com', false],
            ['user', null, 'https://user:pass@www.example.com', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderQuery
     *
     * @param mixed $value
     */
    public function testQuery(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->query()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderQuery(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['query', null, '/www.example.com?query', false],
            ['query', null, '/www.example.com?query', true],
            ['', null, '/www.example.com?', false],
            ['', null, '/www.example.com?', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderScheme
     *
     * @param mixed $value
     */
    public function testScheme(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->scheme()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderScheme(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, '/www.example.com', false],
            [null, null, '/www.example.com', true],
            ['https', null, 'https://www.example.com', false],
            ['https', null, 'https://www.example.com', true],
            [null, null, '', false],
            [null, null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }

    /**
     * @dataProvider dataProviderPath
     *
     * @param mixed $value
     */
    public function testPath(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new ParseUrl(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value', [], []);

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->path()->nullable($failAsNull));
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderPath(): array
    {
        return [
            [null, null, null, false],
            [null, null, null, true],
            [null, null, 'https://www.example.com', false],
            [null, null, 'https://www.example.com', true],
            ['/www.example.com', null, '/www.example.com', false],
            ['/www.example.com', null, '/www.example.com', true],
            ['', null, '', false],
            ['', null, '', true],
            [null, 'be valid url', 'https://', false],
            [null, null, 'https://', true],
        ];
    }
}
