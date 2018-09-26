<?php

namespace rollun\test\datahandler\Filter;

use rollun\datanadler\Filter\RemoveAllExceptDigits;
use PHPUnit\Framework\TestCase;

/**
 * Class RemoveAllExceptDigitsTest
 * @package rollun\test\datahandler\Filter
 */
class RemoveAllExceptDigitsTest extends TestCase
{
    public function dataProvider()
    {
        return [
            [
                'a1b2c3d4',
                ' 1 2 3 4',
            ],
            [
                '(8_n50+=4m7H`74$3%^21',
                ' 8  50  4 7  74 3  21',
            ],
            [
                'abcd',
                '    ',
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
        $filter = new RemoveAllExceptDigits();
        $value = $filter->filter($value);
        $this->assertEquals($value, $expectedValue);
    }
}
