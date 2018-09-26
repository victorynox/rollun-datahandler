<?php

namespace rollun\datanadler\Processor\Factory;

use rollun\datanadler\Processor\ProcessorInterface;
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
 *                  'options' => [],
 *                  'validator' => 'validator-service',
 *              ]
 *          ],
 *      ]
 * ]
 *
 * Class ProcessorAbstractFactory
 * @package rollun\datanadler\Processor\Factory
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
        $validator = $this->getValidator($container, $serviceConfig, $options);
        $processorOptions = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);

        return new $class($processorOptions, $validator);
    }
}
