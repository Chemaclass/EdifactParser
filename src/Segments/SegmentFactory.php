<?php

declare(strict_types=1);

namespace EdifactParser\Segments;

/** @psalm-immutable */
final class SegmentFactory implements SegmentFactoryInterface
{
    public function segmentFromArray(array $rawArray): SegmentInterface
    {
        switch ($rawArray[0]) {
            case 'UNH':
                return new UNHMessageHeader($rawArray);
            case 'DTM':
                return new DTMDateTimePeriod($rawArray);
            case 'NAD':
                return new NADNameAddress($rawArray);
            case 'MEA':
                return new MEADimensions($rawArray);
            case 'CNT':
                return new CNTControl($rawArray);
            case 'PCI':
                return new PCIPackageId($rawArray);
            case 'BGM':
                return new BGMBeginningOfMessage($rawArray);
            case 'UNT':
                return new UNTMessageFooter($rawArray);
        }

        return new UnknownSegment($rawArray);
    }
}
