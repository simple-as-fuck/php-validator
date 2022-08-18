<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Model\RuleChain as RuleChain;
use SimpleAsFuck\Validator\Model\Validated as Validated;
use SimpleAsFuck\Validator\Model\ValueMust as ValueMust;
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
        $rule = new StringRule(null, new RuleChain(), new Validated($value), '');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(ValueMust::class);
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
            [null, 'contains scheme url component', '', false],
            [null, null, 'ftp://', true],
            [null, 'be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'contains scheme url component', 'sadjfguerg', false],
            [null, null, 'ftp://test', true],
            [null, 'be in values list: https', 'ftp://test', false],
            ['https://test', null, 'https://test', true],
            ['https://test', null, 'https://test', false],
            [null, null, 'http://test', true],
            [null, 'be in values list: https', 'http://test', false],
            [null, null, 5, true],
            [null, 'be string', 5, false],
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
        $rule = new StringRule(null, new RuleChain(), new Validated($value), '');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(ValueMust::class);
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
            [null, 'contains scheme url component', '', false],
            [null, null, 'ftp://', true],
            [null, 'be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'contains scheme url component', 'sadjfguerg', false],
            [null, null, 'ftp://test', true],
            [null, 'be in values list: http, https', 'ftp://test', false],
            ['https://test', null, 'https://test', true],
            ['https://test', null, 'https://test', false],
            ['http://test', null, 'http://test', true],
            ['http://test', null, 'http://test', false],
            [null, null, 5, true],
            [null, 'be string', 5, false],
        ];
    }
}
