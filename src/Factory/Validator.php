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
     * @deprecated use SimpleAsFuck\Validator\Factory\Json::make
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
        return Json::make($string, $stringName, $exceptionFactory, $allowInvalidJson, $emptyStringAsNull, $jsonDecodeFlags);
    }

    /**
     * https://jsonlines.org/
     * @deprecated use SimpleAsFuck\Validator\Factory\Jsonl::make
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
        return Jsonl::make($stream, $streamName, $exceptionFactory, $allowInvalidJson, $emptyStringAsNull, $jsonDecodeFlags);
    }
}
