<?php

declare(strict_types=1);

namespace EdifactParser;

use EdifactParser\Segments\SegmentFactory;
use EdifactParser\Segments\SegmentFactoryInterface;
use EdifactParser\Segments\SegmentInterface;

/** @psalm-immutable */
final class SegmentList
{
    private SegmentFactoryInterface $segmentFactory;

    /** @psalm-pure */
    public static function withDefaultFactory(): self
    {
        return new self(SegmentFactory::withDefaultSegments());
    }

    public function __construct(SegmentFactoryInterface $segmentFactory)
    {
        $this->segmentFactory = $segmentFactory;
    }

    /** @return SegmentInterface[] */
    public function fromRaw(array $rawArrays): array
    {
        return array_map(
            fn (array $raw) => $this->segmentFactory->createSegmentFromArray($raw),
            $rawArrays
        );
    }
}
