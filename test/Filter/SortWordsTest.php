<?php

namespace rollun\test\datahandler\Filter;

use rollun\datahandler\Filter\SortWords;
use PHPUnit\Framework\TestCase;

/**
 * Class SortWordsTest
 * @package rollun\test\datahandler\Filter
 */
class SortWordsTest extends TestCase
{
    public function dataProvider()
    {
        return [
            [
                'b a c',
                'a b c',
            ],
            [
                'dem bem aem cem',
                'aem bem cem dem',
            ],
            [
                '2dcx 3dsad 1gfda',
                '1gfda 2dcx 3dsad',
            ],
            [
                '100 99 88',
                '88 99 100',
            ],
            [
                '4aa 3ab 4ac',
                '3ab 4aa 4ac',
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $value
     * @param $expectedValue
     */
    public function testSortWords($value, $expectedValue)
    {
        $filter = new SortWords();
        $value = $filter->filter($value);
        $this->assertEquals($value, $expectedValue);
    }
}
