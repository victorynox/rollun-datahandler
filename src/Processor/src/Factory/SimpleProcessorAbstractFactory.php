<?php

namespace rollun\datahandler\Processor\Factory;

use rollun\datahandler\Processor\ProcessorInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Config example
 *
 * 'processors' => [
 *      'abstract_factory_config' => [
 *          SimpleProcessorAbstractFactory::class => [
 *              'requestedName' => [
 *                  'class' => Concat::class,
 *                  'options' => [
 *                      'validator' => 'validator-service',
 *                      'validatorOptions' => [],
 *                      // other options
 *                  ],
 *              ]
 *          ],
 *      ]
 * ]
 *
 * Class ProcessorAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class SimpleProcessorAbstractFactory extends ProcessorAbstractFactoryAbstract implements AbstractFactoryInterface
{
    /**
     * Parent class. Each object create by this factory must implement or extend this class
     */
    const DEFAULT_CLASS = ProcessorInterface::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $validator = $this->getValidator($container, $pluginOptions);
        $class = $this->getClass($serviceConfig);
        $clearedPluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($clearedPluginOptions, $validator);
    }
}
