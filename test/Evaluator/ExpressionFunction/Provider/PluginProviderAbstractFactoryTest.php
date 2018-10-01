<?php

namespace rollun\test\datahandler\Evaluator\ExpressionFunction\Provider;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use rollun\datahandler\Evaluator\ExpressionFunction\Providers\PluginProviderAbstractFactory;
use rollun\datahandler\Evaluator\ExpressionFunction\Providers\Plugin;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\ValidatorPluginManager;

class PluginProviderAbstractFactoryTest extends TestCase
{
    /**
     * @var PluginProviderAbstractFactory
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new PluginProviderAbstractFactory();
    }

    public function testCanCreate()
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName);

        $this->assertTrue($this->object->canCreate($container, $requestedName));
        $this->assertFalse($this->object->canCreate($container, 'BlaBlaBla'));
    }

    public function testNegativeGetClass()
    {
        $this->expectExceptionMessage(
            "Caused class must implement or extend rollun\datahandler\Evaluator\ExpressionFunction\Providers\Plugin"
        );
        $serviceConfig = [
            'class' => new class()
            {
            },
        ];
        $this->object->getClass($serviceConfig);
    }

    public function testNegativeMissingPluginManagerServiceOptionInvoke()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'pluginManagerService' option in config");
        $this->invoke();
    }

    public function testNegativePluginServicesOptionInvoke()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'pluginServices' option in config");

        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'pluginManagerService' => FilterPluginManager::class
        ]);
        $container->setService(FilterPluginManager::class, new FilterPluginManager($container));
        $this->object->__invoke($container, $requestedName);
    }

    public function testDefaultCalledMethodOptionInvoke()
    {
        $pluginServices = ['a', 'b'];
        $requestedName = 'requestedServiceName';

        // default 'calledMethod' option is '__invoke' is __invoke
        $container = $this->getContainer($requestedName, [
            'pluginManagerService' => FilterPluginManager::class,
            'pluginServices' => $pluginServices,
        ]);
        $container->setService(FilterPluginManager::class, new FilterPluginManager($container));
        $expressionFunctionProvider = $this->object->__invoke($container, $requestedName);
        $this->assertEquals($expressionFunctionProvider->getCalledMethod(), '__invoke');
    }

    public function testPositiveInvoke()
    {
        $requestedName = 'requestedServiceName';
        $pluginManagerService = ValidatorPluginManager::class;
        $pluginServices = ['digits', 'stringTrim'];
        $calledMethod = 'isValid';

        $container = $this->getContainer($requestedName, [
            'pluginManagerService' => $pluginManagerService,
            'pluginServices' => $pluginServices,
            'calledMethod' => $calledMethod,
        ]);
        $container->setService($pluginManagerService, new ValidatorPluginManager($container));

        /** @var Plugin $expressionFunctionProvider */
        $expressionFunctionProvider = $this->object->__invoke($container, $requestedName);

        $this->assertTrue(is_a($expressionFunctionProvider->getPluginManager(), $pluginManagerService, true));
        $this->assertEquals($expressionFunctionProvider->getPluginServices(), $pluginServices);
        $this->assertEquals($expressionFunctionProvider->getCalledMethod(), $calledMethod);
    }

    /**
     * @param $requestedName
     * @param array $serviceConfig
     * @return ServiceManager
     */
    protected function getContainer($requestedName, $serviceConfig = [])
    {
        $container = new ServiceManager();
        $container->setService('config', [
            get_class($this->object) => [
                $requestedName => $serviceConfig
            ]
        ]);

        return $container;
    }

    /**
     * @param array $serviceConfig
     * @return mixed|object
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function invoke($serviceConfig = [])
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, $serviceConfig);
        return $this->object->__invoke($container, $requestedName, null);
    }
}
