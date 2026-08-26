<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\Json;

#[CoversClass(Json::class)]
final class JsonTest extends TestCase
{
    #[DataProvider('dataMakeError')]
    public function testMakeError(string $expectedErrorMessage, string $invalidContent): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage($expectedErrorMessage);

        Json::make($invalidContent, 'Test string')->nullable();
    }

    /**
     * @return non-empty-array<non-empty-array<string>>
     */
    public static function dataMakeError(): array
    {
        return [
            ['Test string must be valid json (Syntax error), invalid content: \'\'', ''],
            ['Test string must be valid json (Syntax error), invalid content: \'kjdfhgroigiosdiugaeiufsabdv\'', 'kjdfhgroigiosdiugaeiufsabdv'],
            ['Test string must be valid json (Syntax error), invalid content: \'kjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigi\' (truncated)', 'kjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdvkjdfhgroigiosdiugaeiufsabdv'],
        ];
    }
}
