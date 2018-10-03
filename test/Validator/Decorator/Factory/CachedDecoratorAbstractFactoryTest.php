<?php

namespace rollun\test\datahandler\Validator\Decorator\Factory;

use rollun\datahandler\Validator\Decorator\Cached;
use rollun\datahandler\Validator\Decorator\Factory\CachedDecoratorAbstractFactory;
use rollun\test\datahandler\Factory\PluginAbstractFactoryAbstractTest;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\Digits;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class CachedDecoratorAbstractFactoryTest
 * @package rollun\test\datahandler\Validator\Decorator\Factory
 */
class CachedDecoratorAbstractFactoryTest extends PluginAbstractFactoryAbstractTest
{
    protected function setUp()
    {
        $this->object = new CachedDecoratorAbstractFactory();
    }

    public function testMainFunctionality()
    {
        $validatorClassName = Cached::class;

        // Assert default class
        $this->assertEquals($this->object->getClass([]), Cached::class);
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

    public function testSameValidators()
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'options' => [
                'validator' => Digits::class
            ]
        ]);

        /** @var Cached $object1 */
        $object1 = $this->object->__invoke($container, $requestedName);
        /** @var Cached $object2 */
        $object2 = $this->object->__invoke($container, $requestedName);

        $this->assertTrue($object1->getCachedValidator() === $object2->getCachedValidator());
    }

    public function testDifferentValidators()
    {
        $requestedName1 = 'requestedServiceName1';
        $requestedName2 = 'requestedServiceName2';
        $container = new ServiceManager();
        $container->setService(ValidatorPluginManager::class, new ValidatorPluginManager($container));
        $container->setService('config', [
            $this->getConstant('KEY') => [
                'abstract_factory_config' => [
                    get_class($this->object) => [
                        $requestedName1 => [
                            'options' => [
                                'validator' => Digits::class
                            ],
                        ],
                        $requestedName2 => [
                            'options' => [
                                'validator' => Digits::class
                            ],
                        ]
                    ],
                ]
            ]
        ]);

        /** @var Cached $object1 */
        $object1 = $this->object->__invoke($container, $requestedName1);
        /** @var Cached $object2 */
        $object2 = $this->object->__invoke($container, $requestedName2);

        $this->assertTrue($object1->getCachedValidator() !== $object2->getCachedValidator());
    }
}
