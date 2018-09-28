<?php

namespace rollun\datahandler\Filter\Factory;

use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterInterface;

/**
 * Create and return instance of FilterInterface
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * 'filters' => [
 *      'abstract_factory_config' => [
 *          SimpleFilterAbstractFactory::class => [
 *              'simpleFilterServiceName1' => [
 *                  'class' => stringTrim::class,
 *                  'options' => [ // by default is not required
 *                      // filter options, specific for each filter
 *                      //...
 *                  ],
 *              ],
 *              'simpleFilterServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 * ],
 * </code>
 *
 * Class SimpleFilterAbstractFactory
 * @package rollun\datahandler\Filter\Factory
 */
class SimpleFilterAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     * Parent class for plugin. By default doesn't set
     */
    const DEFAULT_CLASS = FilterInterface::class;

    /**
     * Common namespace name for plugin config. By default doesn't set
     */
    const KEY = 'filters';

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
        $class = $this->getClass($serviceConfig, true);

        return new $class($pluginOptions);
    }
}
