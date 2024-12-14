<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\Url\ParseProtocolUrl;

/**
 * @covers \SimpleAsFuck\Validator\Rule\Url\ParseProtocolUrl
 */
final class ParseProtocolUrlTest extends TestCase
{
    /**
     * @dataProvider dataProviderNullable
     */
    public function testNullable(?string $expectedValue, ?string $expectedExceptionMessage, string $value, bool $failAsNull): void
    {
        /** @var RuleChain<string> $ruleChain */
        $ruleChain = new RuleChain();
        $rule = new ParseProtocolUrl(
            new UnexpectedValueException(),
            $ruleChain,
            new Validated($value),
            'value',
            [],
            [],
            ['https']
        );

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->nullable($failAsNull));
    }

    /**
     * @return non-empty-array<array{?string, ?non-empty-string, mixed, bool}>
     */
    public function dataProviderNullable(): array
    {
        return [
            ['https://test', null, 'https://test', false],
            ['https://test', null, 'https://test', true],
            [null, 'value must contains one of url schemes: https', 'ftp://test', false],
            [null, null, 'ftp://test', true],
            [null, 'value must contains scheme url component', 'test', false],
            [null, null, 'test', true],
        ];
    }

    /**
     * @dataProvider dataProviderHost
     */
    public function testHost(?string $expectedValue, ?string $expectedExceptionMessage, string $value, bool $failAsNull): void
    {
        /** @var RuleChain<string> $ruleChain */
        $ruleChain = new RuleChain();
        $rule = new ParseProtocolUrl(
            new UnexpectedValueException(),
            $ruleChain,
            new Validated($value),
            'value',
            [],
            [],
            ['https']
        );

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        self::assertSame($expectedValue, $rule->host()->nullable($failAsNull));
    }

    /**
     * @return non-empty-array<array{?string, ?non-empty-string, mixed, bool}>
     */
    public function dataProviderHost(): array
    {
        return [
            ['test', null, 'https://test', false],
            ['test', null, 'https://test', true],
            [null, 'value must contains one of url schemes: https', 'ftp://test', false],
            [null, null, 'ftp://test', true],
            [null, 'value must contains scheme url component', 'test', false],
            [null, null, 'test', true],
        ];
    }
}
