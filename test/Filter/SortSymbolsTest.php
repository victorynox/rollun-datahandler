<?php

namespace rollun\test\datahandler\Filter;

use rollun\datahandler\Filter\SortSymbols;
use PHPUnit\Framework\TestCase;

/**
 * Class SortSymbolsTest
 * @package rollun\test\datahandler\Filter
 */
class SortSymbolsTest extends TestCase
{
    public function dataProvider()
    {
        return [
            [
                'bca',
                'abc',
            ],
            [
                'AccCbB',
                'ABCbcc',
            ],
            [
                '6304916392',
                '0123346699',
            ],
            [
                'bCeA7Ko29M0H1hj',
                '01279ACHKMbehjo',
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $value
     * @param $expectedValue
     */
    public function testSortSymbols($value, $expectedValue)
    {
        $filter = new SortSymbols();
        $value = $filter->filter($value);
        $this->assertEquals($value, $expectedValue);
    }
}
