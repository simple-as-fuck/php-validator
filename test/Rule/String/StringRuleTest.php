<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @covers \SimpleAsFuck\Validator\Rule\String\StringRule
 */
final class StringRuleTest extends TestCase
{
    /**
     * @dataProvider dataProviderParseHttpsUrl
     *
     * @param non-empty-string|null $expectedValue
     * @param non-empty-string|null $expectedExceptionMessage
     * @param mixed $value
     */
    public function testParseHttpsUrl(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $value = $rule->parseHttpsUrl()->nullable($failAsNull);

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderParseHttpsUrl(): array
    {
        return [
            [null, null, null, true],
            [null, null, null, false],
            [null, null, '', true],
            [null, 'value: \'\' must contains scheme url component', '', false],
            [null, null, 'ftp://', true],
            [null, 'value: \'ftp://\' must be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'value: \'sadjfguerg\' must contains scheme url component', 'sadjfguerg', false],
            [null, null, 'ftp://test', true],
            [null, 'value: \'ftp://test\' must contains one of url schemes: https', 'ftp://test', false],
            ['https://test', null, 'https://test', true],
            ['https://test', null, 'https://test', false],
            [null, null, 'http://test', true],
            [null, 'value: \'http://test\' must contains one of url schemes: https', 'http://test', false],
            [null, null, 5, true],
            [null, 'value must be string', 5, false],
        ];
    }

    /**
     * @dataProvider dataProviderParseHttpUrl
     *
     * @param non-empty-string|null $expectedValue
     * @param non-empty-string|null $expectedExceptionMessage
     * @param mixed $value
     */
    public function testParseHttpUrl(?string $expectedValue, ?string $expectedExceptionMessage, $value, bool $failAsNull): void
    {
        $rule = new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $value = $rule->parseHttpUrl()->nullable($failAsNull);

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProviderParseHttpUrl(): array
    {
        return [
            [null, null, null, true],
            [null, null, null, false],
            [null, null, '', true],
            [null, 'value: \'\' must contains scheme url component', '', false],
            [null, null, 'ftp://', true],
            [null, 'value: \'ftp://\' must be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'value: \'sadjfguerg\' must contains scheme url component', 'sadjfguerg', false],
            [null, null, 'ftp://test', true],
            [null, 'value: \'ftp://test\' must contains one of url schemes: http, https', 'ftp://test', false],
            ['https://test', null, 'https://test', true],
            ['https://test', null, 'https://test', false],
            ['http://test', null, 'http://test', true],
            ['http://test', null, 'http://test', false],
            [null, null, 5, true],
            [null, 'value must be string', 5, false],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param mixed $input
     */
    public function test(?string $expectedString, $input, bool $emptyAsNull): void
    {
        $rule = new StringRule(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($input),
            'value',
            false,
            $emptyAsNull
        );

        self::assertSame($expectedString, $rule->nullable());
    }

    /**
     * @return non-empty-array<array{?string, mixed, bool}>
     */
    public function dataProvider(): array
    {
        return [
            ['', '', false],
            [null, '', true],
        ];
    }
}
