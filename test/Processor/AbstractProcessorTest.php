<?php

namespace test\Vehicles\Workers\Processors;

use PHPUnit\Framework\TestCase;
use rollun\datanadler\Processor\ProcessorInterface;
use rollun\datanadler\Processor\ProcessorPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class AbstractProcessorTest
 * @package test\Vehicles\Workers\Processors
 */
class AbstractProcessorTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $container;

    /**
     * @var ValidatorPluginManager
     */
    protected $validatorPluginManager;

    /**
     * @var ProcessorPluginManager
     */
    protected $processorPluginManager;

    public function validatorDataProvider()
    {
        return [
            [
                $this->getProcessorPluginManager()->build('concat', [
                    'validator' => 'isCountable',
                    'options' => [
                        'columns' => [1, 2],
                        'columnToWrite' => 3
                    ]
                ]),
                [
                    1 => 'a',
                    2 => 'b',
                ],
                [
                    1 => 'a',
                    2 => 'b',
                    3 => 'a_b',
                ]
            ],
            [
                $this->getProcessorPluginManager()->build('concat', [
                    'validator' => 'digits',
                    'options' => [
                        'columns' => [1, 2],
                        'columnToWrite' => 3
                    ]
                ]),
                [
                    1 => 'a',
                    2 => 'b',
                ],
                [
                    1 => 'a',
                    2 => 'b',
                ]
            ],
        ];
    }

    /**
     * @return ServiceManager
     */
    protected function getContainer()
    {
        if ($this->container === null) {
            global $container;
            $this->container = isset($container) ? $container : include "config/container.php";
        }

        return $this->container;
    }

    /**
     * @return ProcessorPluginManager
     */
    protected function getProcessorPluginManager()
    {
        if ($this->processorPluginManager === null) {
            $this->processorPluginManager = $this->getContainer()->get(ProcessorPluginManager::class);
        }

        return $this->processorPluginManager;
    }

    /**
     * @dataProvider validatorDataProvider
     * @param $processor
     * @param $value
     * @param $expectedValue
     */
    public function testValidator(ProcessorInterface $processor, $value, $expectedValue)
    {
        $value = $processor->process($value);
        $this->assertEquals($value, $expectedValue);
    }
}