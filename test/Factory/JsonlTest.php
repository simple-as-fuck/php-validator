<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\Jsonl;

#[CoversClass(Jsonl::class)]
final class JsonlTest extends TestCase
{
    /**
     * @param array<mixed> $expectedData
     */
    #[DataProvider('dataMake')]
    public function testMake(
        array   $expectedData,
        string  $jsonl,
        bool    $allowInvalidJson = false,
        ?string $expectedErrorMessage = null,
    ): void {
        if ($expectedErrorMessage !== null) {
            $this->expectExceptionMessage($expectedErrorMessage);
        }

        $data = [];
        $stream = Jsonl::make(Utils::streamFor($jsonl), allowInvalidJson: $allowInvalidJson);
        while ($stream->valid()) {
            $stream->current();
            $stream->next();
        }
        foreach ($stream as $item) {
        }
        $stream->rewind();
        while ($stream->valid()) {
            $stream->current();
            $stream->next();
        }
        foreach ($stream as $lineNumber => $value) {
            $data[$lineNumber] = $value->nullable();
        }

        self::assertEquals($expectedData, $data);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataMake(): array
    {
        return [
            [[], ''],
            [[], '', true],

            [[], ' ', false, 'stream content value 1 must be valid json (Syntax error), invalid content: \' \''],
            [[1 => null], ' ', true],
            [[], "\n", false, 'stream content value 1 must be valid json (Syntax error), invalid content: \'\''],
            [[1 => null], "\n", true],
            [[], "1\n{\"test\":5}\n fuck \n[8.9]", false, 'stream content value 3 must be valid json (Syntax error), invalid content: \' fuck \''],
            [[1 => 1, (object)['test' => 5], null, [8.9]], "1\n{\"test\":5}\n fuck \n[8.9 ]", true],
            [[], "5\n\n9", false, 'stream content value 2 must be valid json (Syntax error), invalid content: \'\''],
            [[1 => 5, 2 => null, 3 => 9], "5\n\n9", true],

            [[1 => 1, 2 => 9], "1\n9"],
            [[1 => 3, 2 => '5', 3 => 9], "3\n\"5\"\n9\n"],
            [[1 => [], 2 => false, 3 => 9], "[]\nfalse\n9"],
            [[1 => null, 2 => false, 3 => (object)[]], "null\nfalse\n{}\n"],
            [[1 => 5.9, 2 => 'ahoj', [5, 8]], " 5.9\n \"ahoj\" \r\n[5, 8]"],
            [[1 => 8973, 'ahoj hi', 'jkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdi'], "8973\n\"ahoj hi\"\n\"jkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdijkfgshdkjfhdsghhfdigfsdi\"\n"],
        ];
    }

    #[DataProvider('dataMakeError')]
    public function testMakeError(string $expectedErrorMessage, string $invalidContent): void
    {
        $this->expectExceptionMessage($expectedErrorMessage);



        $stream = Jsonl::make(Utils::streamFor($invalidContent));
        foreach ($stream as $item) {
            $item->int()->positive()->max(500)->notNull();
        }
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataMakeError(): array
    {
        return [
            ['stream content value 1 json must be not null', "null\n"],
            ['stream content value 1 json must be integer, object given', "{\"test\":5}\n"],
            ['stream content value 2 json must have minimum value: 1', "8\n0\n"],
            ['stream content value 3 json must have maximum value: 500', "8\n65\n6455\n"],
        ];
    }
}
