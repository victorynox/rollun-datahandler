<?php

namespace rollun\test\datahandler\Validator\Factory;

use rollun\datahandler\Validator\ArrayAdapter;
use rollun\datahandler\Validator\Factory\ValidatorAdapterAbstractFactory;
use rollun\test\datahandler\Factory\PluginAbstractFactoryAbstractTest;
use Zend\Validator\Digits;
use Zend\Validator\ValidatorPluginManager;

class ValidatorAdapterAbstractFactoryTest extends PluginAbstractFactoryAbstractTest
{
    protected function setUp()
    {
        $this->object = new ValidatorAdapterAbstractFactory();
    }

    public function testMainFunctionality()
    {
        $validatorClassName = ArrayAdapter::class;
        $this->assertPositiveGetClass($validatorClassName);
    }

    /**
     * @param $requestedName
     * @param array $serviceConfig
     * @return \Zend\ServiceManager\ServiceManager
     * @throws \ReflectionException
     */
    protected function getContainer($requestedName, $serviceConfig = [])
    {
        $container = parent::getContainer($requestedName, $serviceConfig);
        $container->setService(ValidatorPluginManager::class, new ValidatorPluginManager($container));

        return $container;
    }

    public function testValidOption()
    {
        $validatorClassName = Digits::class;
        $validatorAdapterClassName = ArrayAdapter::class;
        $columnsToValidate = ['a', 'b'];

        /** @var ArrayAdapter $validatorAdapter */
        $validatorAdapter = $this->invoke([
            'class' => $validatorAdapterClassName,
            'options' => [
                'columnsToValidate' => $columnsToValidate,
                'validator' => $validatorClassName,
            ]
        ]);

        $this->assertEquals($validatorAdapter->getColumnsToValidate(), $columnsToValidate);
        $this->assertTrue(is_a($validatorAdapter->getValidator(), $validatorClassName, true));

    }
}
