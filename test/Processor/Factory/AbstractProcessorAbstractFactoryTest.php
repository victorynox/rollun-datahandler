<?php

namespace rollun\test\datahandler\Processor\Factory;

use rollun\test\datahandler\Factory\PluginAbstractFactoryAbstractTest;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class ProcessorAbstractFactoryAbstractTest
 * @package rollun\test\datahandler\Processor\Factory
 */
abstract class AbstractProcessorAbstractFactoryTest extends PluginAbstractFactoryAbstractTest
{
    /**
     * @param $requestedName
     * @param $serviceConfig
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getContainer($requestedName, $serviceConfig = [])
    {
        $container = parent::getContainer($requestedName, $serviceConfig);
        $container->setService(ValidatorPluginManager::class, new ValidatorPluginManager($container));

        return $container;
    }

    /**
     * @param $processorClassName
     * @param $validatorClassName
     */
    public function assertInvokeWithOptions($processorClassName, $validatorClassName)
    {
        $serviceConfig = [
            'class' => $processorClassName,
            'options' => [
                'validator' => $validatorClassName,
            ]
        ];

        $processor = $this->invoke($serviceConfig);

        $this->assertTrue(is_a($processor, $processorClassName, true));
        $this->assertTrue(is_a($processor->getValidator(), $validatorClassName, true));
    }

    /**
     * @param $processorClassName
     * @param $validatorClassName
     */
    public function assertInvokeWithConfig($processorClassName, $validatorClassName)
    {
        $serviceConfig = [
            'class' => $processorClassName
        ];
        $options = [
            'validator' => $validatorClassName,
        ];
        $processor = $this->invoke($serviceConfig, $options);

        $this->assertTrue(is_a($processor, $processorClassName, true));
        $this->assertTrue(is_a($processor->getValidator(), $validatorClassName, true));
    }
}
