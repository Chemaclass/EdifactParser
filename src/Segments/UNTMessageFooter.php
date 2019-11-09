<?php

declare(strict_types=1);

namespace EdifactParser\Segments;

final class UNTMessageFooter implements SegmentInterface
{
    public const NAME = 'UNT';

    /** @var array */
    private $rawValues;

    public function __construct(array $rawValues)
    {
        $this->rawValues = $rawValues;
    }

    public function name(): string
    {
        return self::NAME;
    }

    public function subSegmentKey(): string
    {
        return $this->rawValues[1];
    }

    public function rawValues(): array
    {
        return $this->rawValues;
    }
}