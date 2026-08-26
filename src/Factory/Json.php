<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

use JsonMachine\Exception\JsonMachineException;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\PassThruDecoder;
use Psr\Http\Message\StreamInterface;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\String\ParseJson;

final class Json
{
    /**
     * @param non-empty-string $stringName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     */
    public static function make(
        string $string,
        string $stringName = 'string',
        Exception $exceptionFactory = new UnexpectedValueException(),
        bool $allowInvalidJson = false,
        bool $emptyStringAsNull = false,
        int $jsonDecodeFlags = 0,
    ): ParseJson {
        return (new ParseJson(
            $exceptionFactory,
            /** @phpstan-ignore-next-line argument.type */
            new RuleChain(),
            new Validated($string),
            $stringName,
            allowInvalidJson: $allowInvalidJson,
            emptyStringAsNull: $emptyStringAsNull,
            jsonDecodeFlags: $jsonDecodeFlags,
        ))
            ->cache()
        ;
    }

    /**
     * @param non-empty-string $streamName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     * @return \Iterator<array-key, ParseJson>
     */
    public static function makeIterator(
        StreamInterface $stream,
        string $streamName = 'stream content',
        Exception $exceptionFactory = new UnexpectedValueException(),
        bool $allowInvalidJson = false,
        bool $emptyStringAsNull = false,
        int $jsonDecodeFlags = 0,
    ): \Iterator {
        try {
            $items = Items::fromStream(
                $stream->detach() ?? throw new \RuntimeException('Detach stream missing'),
                ['decoder' => new PassThruDecoder()]
            );
            foreach ($items as $key => $item) {
                $key = IntRule::make($key)->nullable(failAsNull: true)
                    ??
                    (new ParseJson(
                        $exceptionFactory,
                        /** @phpstan-ignore-next-line argument.type */
                        new RuleChain(),
                        new Validated($key),
                        $streamName,
                        parsedValueName: 'json key',
                    ))
                        ->string()
                        ->notNull()
                ;

                yield $key => (new ParseJson(
                    $exceptionFactory,
                    /** @phpstan-ignore-next-line argument.type */
                    new RuleChain(),
                    new Validated($item),
                    $streamName,
                    allowInvalidJson: $allowInvalidJson,
                    emptyStringAsNull: $emptyStringAsNull,
                    jsonDecodeFlags: $jsonDecodeFlags,
                    parsedValueName: 'json' . (is_string($key) ? '->' . $key : '[' . $key . ']'),
                ))
                    ->cache()
                ;
            }
        } catch (JsonMachineException $exception) {
            $message = $exception->getMessage();
            if ($message === '') {
                throw $exceptionFactory->create($streamName . ' has unknow error');
            }
            throw $exceptionFactory->create($streamName . ' has error: ' . $message);
        }
    }
}
