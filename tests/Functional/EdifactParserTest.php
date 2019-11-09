<?php

declare(strict_types=1);

use EdifactParser\EdifactParser;
use EdifactParser\Exception\InvalidFile;
use EdifactParser\Segments\CNTControl;
use EdifactParser\Segments\UNHMessageHeader;
use EdifactParser\Segments\UNTMessageFooter;
use PHPUnit\Framework\TestCase;

final class EdifactParserTest extends TestCase
{
    /** @test */
    public function invalidFileDueToANonPrintableChar(): void
    {
        $fileContent = <<<EDI
\xE2\x80\xAF
EDI;
        $this->expectException(InvalidFile::class);
        EdifactParser::parse($fileContent);
    }

    /** @test */
    public function parseMoreThanOneMessage(): void
    {
        $fileContent = <<<EDI
UNA:+.? '
UNH+1+IFTMIN:S:93A:UN:PN001'
UNT+19+1'
UNH+2+IFTMIN:S:94A:UN:PN002'
UNT+19+2'
UNH+3+IFTMIN:S:94A:UN:PN003'
UNT+19+3'
UNZ+3+4'
EDI;
        $transactionResult = EdifactParser::parse($fileContent);
        self::assertCount(3, $transactionResult->messages());
    }

    /** @test */
    public function extractValuesFromMessage(): void
    {
        $fileContent = <<<EDI
UNA:+.? '
UNH+1+IFTMIN:S:93A:UN:PN001'
CNT+7:0.1:KGM'
CNT+11:1:PCE'
UNT+19+1'
UNZ+1+3'
EDI;
        $transactionResult = EdifactParser::parse($fileContent);
        self::assertCount(1, $transactionResult->messages());
        $firstMessage = $transactionResult->messages()[0];
        $segments = $firstMessage->segments();

        /** @var UNHMessageHeader $unh */
        $unh = $segments[UNHMessageHeader::NAME]['1'];
        self::assertEquals(['UNH', '1', ['IFTMIN', 'S', '93A', 'UN', 'PN001']], $unh->rawValues());

        /** @var CNTControl $cnt7 */
        $cnt7 = $segments[CNTControl::NAME]['7'];
        self::assertEquals(['CNT', ['7', '0.1', 'KGM']], $cnt7->rawValues());

        /** @var CNTControl $cnt11 */
        $cnt11 = $segments[CNTControl::NAME]['11'];
        self::assertEquals(['CNT', ['11', '1', 'PCE']], $cnt11->rawValues());

        /** @var UNTMessageFooter $unt */
        $unt = $segments[UNTMessageFooter::NAME]['19'];
        self::assertEquals(['UNT', '19', '1'], $unt->rawValues());
    }
}