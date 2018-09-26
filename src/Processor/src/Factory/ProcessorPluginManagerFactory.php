<?php

namespace rollun\datahandler\Processor\Factory;

use rollun\datahandler\Processor\ProcessorPluginManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProcessorPluginManagerAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class ProcessorPluginManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ProcessorPluginManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $pluginManager = new ProcessorPluginManager($container, $options ?: []);

        // If this is in a zend-mvc application, the ServiceListener will inject
        // merged configuration during bootstrap.
        if ($container->has('ServiceListener')) {
            return $pluginManager;
        }

        // If we do not have a config service, nothing more to do
        if (!$container->has('config')) {
            return $pluginManager;
        }

        $config = $container->get('config');

        // If we do not have filters configuration, nothing more to do
        if (!isset($config[ProcessorAbstractFactoryAbstract::KEY])
            || !is_array($config[ProcessorAbstractFactoryAbstract::KEY])
        ) {
            return $pluginManager;
        }

        // Wire service configuration for validators
        (new Config($config[ProcessorAbstractFactoryAbstract::KEY]))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}
