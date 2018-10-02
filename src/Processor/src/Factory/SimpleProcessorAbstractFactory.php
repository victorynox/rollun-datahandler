<?php

namespace rollun\datahandler\Processor\Factory;

use rollun\datahandler\Processor\ProcessorInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ProcessorInterface
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * 'processors' => [
 *      'abstract_factory_config' => [
 *          SimpleProcessorAbstractFactory::class => [
 *              'simpleProcessorServiceName1' => [
 *                  'class' => Concat::class,
 *                  'options' => [ // by default is not required
 *                      'validator' => 'validator-service',
 *                      'validatorOptions' => [],
 *                      // other options, specific for each processor
 *                      //...
 *                  ],
 *              ],
 *              'simpleProcessorServiceName2' => [
 *                  '//...
 *              ],
 *          ],
 *      ]
 * ],
 * </code>
 *
 * Class ProcessorAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class SimpleProcessorAbstractFactory extends AbstractProcessorAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Parent class. Each object create by this factory must implement or extend this class
     */
    const DEFAULT_CLASS = ProcessorInterface::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $validator = $this->getValidator($container, $pluginOptions);
        $class = $this->getClass($serviceConfig, true);
        $clearedPluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($clearedPluginOptions, $validator);
    }
}
