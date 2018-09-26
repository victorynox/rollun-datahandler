<?php

namespace test\Vehicles\Workers\Processors;

use rollun\datanadler\Processor\Concat;

class ConcatTest extends AbstractProcessorTest
{
    public function dataProvider()
    {
        return [
            [
                [
                    'columns' => [
                        1 => 'first column',
                        2 => 'second column',
                        3 => 'third column',
                    ],
                    'columnToWrite' => 'result column',
                ],
                [
                    'first column' => 'a',
                    'second column' => 'b',
                    'third column' => 'c'
                ],
                [
                    'column' => 'result column',
                    'result' => 'a_b_c',
                ]
            ],
            [
                [
                    'columns' => [
                        1 => 'first column',
                        2 => 'second column',
                    ],
                    'delimiter' => '-',
                    'columnToWrite' => 'result column',
                ],
                [
                    'first column' => 'a',
                    'second column' => 'b',
                    'third column' => 'c'
                ],
                [
                    'column' => 'result column',
                    'result' => 'a-b',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $options
     * @param $value
     * @param $expected
     */
    public function testProcess($options, $value, $expected)
    {
        $object = new Concat($options);
        $processed = $object->process($value);
        $this->assertEquals($processed[$expected['column']], $expected['result']);
    }
}
