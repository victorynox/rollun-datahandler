<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Providers;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use rollun\datahandler\Evaluator\ExpressionFunctionProviders\Plugin;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ExpressionFunctionProviderInterface
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * ProviderAbstractFactory::class => [
 *      'pluginProviderServiceName1' => [
 *          'class' => ExpressionEvaluatorFactory::class, // default value
 *          'pluginManagerService' => FilterPluginManager::class,
 *          'calledMethod' => 'filter,
 *          'pluginServices' => [
 *               'digits',
 *               'rqlReplace',
 *               //...
 *           ]
 *      ],
 *      'providerName2' => [
 *          //...
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class PluginProviderAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * Parent class for function
     */
    const DEFAULT_CLASS = Plugin::class;

    /**
     * Config for plugin manager service
     */
    const PLUGIN_MANAGER_SERVICE_KEY = 'pluginManagerService';

    /**
     * Config for plugin manager called method
     */
    const CALLED_METHOD_KEY = 'calledMethod';

    /**
     * Config plugin manager services key
     */
    const PLUGIN_SERVICES_KEY = 'pluginServices';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return isset($container->get('config')[self::class][$requestedName]);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $container->get('config')[self::class][$requestedName];

        if (!isset($serviceConfig[self::PLUGIN_MANAGER_SERVICE_KEY])) {
            throw new InvalidArgumentException("Missing 'pluginManagerService' option in config");
        }

        if (!isset($serviceConfig[self::CALLED_METHOD_KEY])) {
            throw new InvalidArgumentException("Missing 'calledMethod' option in config");
        }

        if (!isset($serviceConfig[self::PLUGIN_SERVICES_KEY])) {
            throw new InvalidArgumentException("Missing 'pluginServices' option in config");
        }

        $class = $this->getClass($serviceConfig);
        $pluginManager = $container->get($serviceConfig[self::PLUGIN_MANAGER_SERVICE_KEY]);
        $pluginServices = $container->get($serviceConfig[self::PLUGIN_SERVICES_KEY]);
        $calledMethod = $container->get($serviceConfig[self::CALLED_METHOD_KEY]);

        return new $class($pluginManager, $pluginServices, $calledMethod);
    }

    /**
     * Get caused class
     *
     * @param array $serviceConfig
     * @return mixed
     */
    protected function getClass(array $serviceConfig)
    {
        if (!isset($serviceConfig[self::CLASS_KEY])) {
            return self::DEFAULT_CLASS;
        }

        if (!is_a($serviceConfig[self::CLASS_KEY], static::DEFAULT_CLASS, true)) {
            throw new \InvalidArgumentException(
                'Caused class must implement or extend ' . static::DEFAULT_CLASS
            );
        }

        return $serviceConfig[self::CLASS_KEY];
    }
}
