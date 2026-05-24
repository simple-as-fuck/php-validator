<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

use Psr\Http\Message\StreamInterface;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rules;
use SimpleAsFuck\Validator\Rule\String\ParseJson;

final class Validator
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable', Exception $exceptionFactory = new UnexpectedValueException()): Rules
    {
        return new Rules($exceptionFactory, $valueName, new Validated($value));
    }

    /**
     * @param non-empty-string $stringName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     */
    public static function json(
        string $string,
        string $stringName = 'string',
        Exception $exceptionFactory = new UnexpectedValueException(),
        bool $allowInvalidJson = false,
        bool $emptyStringAsNull = false,
        int $jsonDecodeFlags = 0,
    ): ParseJson {
        return ParseJson::make($string, $stringName, $exceptionFactory, $allowInvalidJson, $emptyStringAsNull, $jsonDecodeFlags)->cache();
    }

    /**
     * https://jsonlines.org/
     * @param non-empty-string $streamName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     * @return \Iterator<int, ParseJson>
     */
    public static function jsonl(
        StreamInterface $stream,
        string $streamName = 'stream content',
        Exception $exceptionFactory = new UnexpectedValueException(),
        bool $allowInvalidJson = false,
        bool $emptyStringAsNull = false,
        int $jsonDecodeFlags = 0,
    ): \Iterator {
        return new class ($stream, $streamName, $exceptionFactory, $allowInvalidJson, $emptyStringAsNull, $jsonDecodeFlags) implements \Iterator {
            private int $lineNumber = 0;
            private string $buffer = '';
            private ?ParseJson $current = null;

            public function __construct(
                private readonly StreamInterface $stream,
                private readonly string $streamName,
                private readonly Exception $exceptionFactory,
                private readonly bool $allowInvalidJson,
                private readonly bool $emptyStringAsNull,
                private readonly int $jsonDecodeFlags,
            ) {
                $this->fetch();
            }

            public function key(): ?int
            {
                if ($this->valid()) {
                    return $this->lineNumber;
                }

                return null;
            }

            public function current(): ParseJson
            {
                if ($this->valid()) {
                    return $this->current ?? throw $this->exceptionFactory->create($this->streamName . ' value ' . $this->lineNumber . ' is not valid stream');
                }

                throw $this->exceptionFactory->create($this->streamName . ' value ' . $this->lineNumber . ' is not valid stream');
            }

            public function next(): void
            {
                $this->fetch();
            }

            public function valid(): bool
            {
                return $this->current !== null || ! $this->stream->eof() || $this->buffer !== '';
            }

            public function rewind(): void
            {
                if ($this->lineNumber <= 1) {
                    return;
                }

                $this->stream->rewind();
                $this->lineNumber = 0;
                $this->buffer = '';
                $this->fetch();
            }

            private function fetch(): void
            {
                if (!$this->valid()) {
                    return;
                }

                $this->lineNumber++;

                while (!$this->stream->eof()) {
                    $this->buffer .= $this->stream->read(1024);
                    $endLine = strpos($this->buffer, "\n");
                    if ($endLine !== false) {
                        break;
                    }
                }

                if ($this->buffer === '') {
                    $this->current = null;
                    return;
                }

                $endLine = strpos($this->buffer, "\n");
                if ($endLine === false) {
                    $line = $this->buffer;
                    $this->buffer = '';
                } else {
                    $line = substr($this->buffer, 0, $endLine);
                    $this->buffer = substr($this->buffer, $endLine + 1);
                }

                $this->current = Validator::json(
                    $line,
                    $this->streamName . ' value ' . $this->lineNumber,
                    $this->exceptionFactory,
                    $this->allowInvalidJson,
                    $this->emptyStringAsNull,
                    $this->jsonDecodeFlags,
                );
            }
        };
    }
}
