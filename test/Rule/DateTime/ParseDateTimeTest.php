<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\String\StringRule;

/**
 * @covers \SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime
 */
final class ParseDateTimeTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param class-string<\DateTimeInterface> $dateTimeClass
     */
    public function test(\DateTimeInterface $expectedDateTime, string $input, string $format, string $dateTimeClass): void
    {
        /** @var mixed $input */
        $rule = new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($input), 'value');
        $rule = new ParseDateTime($rule, '', $format, $dateTimeClass);

        self::assertInstanceOf($dateTimeClass, $rule->nullable());
        self::assertEquals($expectedDateTime, $rule->nullable());
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProvider(): array
    {
        return [
            [\DateTime::createFromFormat('Y-m-d H:i:s', '1999-05-06 10:15:16'), '1999-05-06 10:15:16', 'Y-m-d H:i:s', \DateTime::class],
            [\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1999-05-06 10:15:16'), '1999-05-06 10:15:16', 'Y-m-d H:i:s', \DateTimeImmutable::class],
            [\DateTime::createFromFormat('Y-m-d', '1999-05-06'), '1999-05-06', 'Y-m-d', \DateTime::class],
            [\DateTime::createFromFormat('Y-m-d', '1999-05-06'), '990506', 'ymd', \DateTime::class],
            [\DateTime::createFromFormat('Y-m-d', '2015-01-02'), '150102', 'ymd', \DateTime::class],
        ];
    }
}
