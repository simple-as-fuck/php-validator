<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;

/**
 * @covers \SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime
 */
final class ParseDateTimeTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param non-empty-string $expectedDateTime
     * @param non-empty-string $expectedFormat
     * @param non-empty-string $format
     * @param class-string<\DateTimeInterface> $dateTimeClass
     * @param non-empty-string|null $timezone
     */
    public function test(string $expectedDateTime, string $expectedFormat, string $input, string $format, string $dateTimeClass, ?string $timezone): void
    {
        /** @var mixed $input */
        $rule = new ParseDateTime(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($input),
            'value',
            $format,
            $dateTimeClass,
            $timezone
        );

        self::assertInstanceOf($dateTimeClass, $rule->notNull());
        self::assertSame($expectedDateTime, $rule->notNull()->format($expectedFormat));
    }

    /**
     * @return non-empty-array<array{non-empty-string, non-empty-string, string, non-empty-string, class-string<\DateTimeInterface>, non-empty-string|null}>
     */
    public function dataProvider(): array
    {
        return [
            ['1999-05-06 10:15:16', 'Y-m-d H:i:s', '1999-05-06 10:15:16', 'Y-m-d H:i:s', \DateTime::class, null],
            ['1999-05-06 10:15:16', 'Y-m-d H:i:s', '1999-05-06 10:15:16', 'Y-m-d H:i:s', \DateTimeImmutable::class, null],
            ['1999-05-06', 'Y-m-d', '1999-05-06', 'Y-m-d', \DateTime::class, null],
            ['1999-05-06', 'Y-m-d', '990506', 'ymd', \DateTime::class, null],
            ['2015-01-02', 'Y-m-d', '150102', 'ymd', \DateTime::class, null],
            ['2015-01-02T00:00:00+00:00', \DateTimeInterface::ATOM, '2015-01-02T00:00:00+00:00', \DateTimeInterface::ATOM, \DateTime::class, null],
            ['2015-01-02T04:00:00+02:00', \DateTimeInterface::ATOM, '2015-01-02T02:00:00+00:00', \DateTimeInterface::ATOM, \DateTime::class, '+02:00'],
        ];
    }
}
