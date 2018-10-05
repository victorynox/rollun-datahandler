<?php

namespace rollun\test\datahandler\Filter;

use rollun\datahandler\Filter\RqlReplace;
use PHPUnit\Framework\TestCase;

/**
 * Class RqlReplaceTest
 * @package rollun\test\datahandler\Filter
 */
class RqlReplaceTest extends TestCase
{
    /**
     * @param $options
     * @return RqlReplace
     */
    public function init($options)
    {
        return new RqlReplace($options);
    }

    public function filterDataProvider()
    {
        return [
            [
                'expect' => 'The simple to cut',
                'options' => [
                    'beforePattern' => '',
                    'pattern' => 'lest examp',
                    'afterPattern' => '',
                ],
                'value' => 'The simplest example to cut',
            ],
            [
                'expect' => 'First  important',
                'options' => [
                    'beforePattern' => 'First ',
                    'pattern' => 'characters',
                    'afterPattern' => '',
                ],
                'value' => 'First characters important',
            ],
            [
                'expect' => 'Bayou 300 ',
                'options' => [
                    'beforePattern' => 'Bayou 300 ',
                    'pattern' => 'KLF*',
                    'afterPattern' => '',
                ],
                'value' => 'Bayou 300 KLF300A',
            ],
            [
                'expect' => 'Whooo  - hoooome!',
                'options' => [
                    'beforePattern' => 'Whooo ',
                    'pattern' => '78*',
                    'afterPattern' => ' -',
                ],
                'value' => 'Whooo 7888 - hoooome!',
            ],
            [
                'expect' => '',
                'options' => [
                    'beforePattern' => '',
                    'pattern' => ' * * * ',
                    'afterPattern' => '',
                ],
                'value' => ' Remove white spaces ',
            ],
            [
                'expect' => 'Brute Force 750 4x4i EPS',
                'options' => [
                    'beforePattern' => 'Brute Force 750 ',
                    'pattern' => 'KVF*',
                    'afterPattern' => '4x4i',
                ],
                'value' => 'Brute Force 750 KVF750G 4x4i EPS',
            ],
        ];
    }

    /**
     * @dataProvider filterDataProvider
     * @param $expect
     * @param $options
     * @param $value
     */
    public function testFilter($expect, $options, $value)
    {
        $filter = $this->init($options);
        $value = $filter->filter($value);

        $this->assertEquals($expect, $value);
    }
}
