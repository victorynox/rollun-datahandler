<?php

namespace test\Vehicles\Workers\Processors;

use rollun\datanadler\Processor\FilterApplier;
use Zend\Filter\FilterPluginManager;

/**
 * Class FilterApplierTest
 * @package test\Vehicles\Workers\Processors
 */
class FilterApplierTest extends AbstractProcessorTest
{
    public function getFilterPluginManager()
    {
        global $container;
        $container = isset($container) ? $container : include "config/container.php";

        return $container->get(FilterPluginManager::class);
    }

    public function dataProvider()
    {
        return [
            [
                [
                    'columnToRead' => 'some column',
                    'filters' => [
                        [
                            'service' => 'pregReplace',
                            'options' => [
                                'pattern' => '/cd/'
                            ]
                        ]
                    ]
                ],
                [
                    'some column' => 'abcdf',
                ],
                [
                    'some column' => 'abf',
                ],
            ],
            [
                [
                    'columnToRead' => 'some column',
                    'columnToWrite' => 'result column',
                    'filters' => [
                        [
                            'service' => 'rqlReplace',
                            'options' => [
                                'pattern' => 'ab'
                            ]
                        ],
                        [
                            'service' => 'stringTrim',
                        ],
                    ]
                ],
                [
                    'some column' => '   abcd   ',
                ],
                [
                    'some column' => '   abcd   ',
                    'result column' => 'cd',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $options
     * @param $value
     * @param $expected
     * @throws \Exception
     */
    public function testProcess($options, $value, $expected)
    {
        $filterPluginManager = $this->getFilterPluginManager();
        $object = new FilterApplier($options, $filterPluginManager);
        $result = $object->process($value);
        $this->assertEquals($expected, $result);
    }
}
