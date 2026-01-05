<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\String\StringRule;

#[CoversClass(StringRule::class)]
final class StringRuleTest extends TestCase
{
    /**
     * @param non-empty-string|null $expectedValue
     * @param non-empty-string|null $expectedExceptionMessage
     */
    #[DataProvider('dataProviderHttpsUrl')]
    public function testHttpsUrl(?string $expectedValue, ?string $expectedExceptionMessage, mixed $value, bool $failAsNull): void
    {
        $rule = new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $value = $rule->httpsUrl()->nullable($failAsNull);

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataProviderHttpsUrl(): array
    {
        return [
            [null, null, null, true],
            [null, null, null, false],
            [null, null, '', true],
            [null, 'value: \'\' must contains one of url schemes: https', '', false],
            [null, null, 'ftp://', true],
            [null, 'value: \'ftp://\' must be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'value: \'sadjfguerg\' must contains one of url schemes: https', 'sadjfguerg', false],
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
     * @param non-empty-string|null $expectedValue
     * @param non-empty-string|null $expectedExceptionMessage
     */
    #[DataProvider('dataProviderHttpUrl')]
    public function testHttpUrl(?string $expectedValue, ?string $expectedExceptionMessage, mixed $value, bool $failAsNull): void
    {
        $rule = new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), 'value');

        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $value = $rule->httpUrl()->nullable($failAsNull);

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataProviderHttpUrl(): array
    {
        return [
            [null, null, null, true],
            [null, null, null, false],
            [null, null, '', true],
            [null, 'value: \'\' must contains one of url schemes: http, https', '', false],
            [null, null, 'ftp://', true],
            [null, 'value: \'ftp://\' must be valid url', 'ftp://', false],
            [null, null, 'sadjfguerg', true],
            [null, 'value: \'sadjfguerg\' must contains one of url schemes: http, https', 'sadjfguerg', false],
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
}
