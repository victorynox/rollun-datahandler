<?php

namespace rollun\test\datahandler\Validator\Decorator\Factory;

use rollun\datahandler\Validator\Decorator\Factory\ThrowableDecoratorAbstractFactory;
use rollun\datahandler\Validator\Decorator\Throwable;
use Zend\Validator\Digits;
use rollun\test\datahandler\Factory\PluginAbstractFactoryAbstractTest;
use Zend\Validator\ValidatorPluginManager;

class ThrowableDecoratorAbstractFactoryTest extends PluginAbstractFactoryAbstractTest
{
    protected function setUp()
    {
        $this->object = new ThrowableDecoratorAbstractFactory();
    }

    public function testMainFunctionality()
    {
        $validatorClassName = Throwable::class;

        // Assert default class
        $this->assertEquals($this->object->getClass([]), Throwable::class);
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
        $validatorAdapterClassName = Throwable::class;

        /** @var Throwable $validatorDecorator */
        $validatorDecorator = $this->invoke([
            'class' => $validatorAdapterClassName,
            'options' => [
                'validator' => $validatorClassName,
            ]
        ]);

        $this->assertTrue(is_a($validatorDecorator->getValidator(), $validatorClassName, true));
    }
}
