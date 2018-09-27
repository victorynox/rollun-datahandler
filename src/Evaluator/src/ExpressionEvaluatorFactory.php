<?php

namespace rollun\datahandler\Evaluator;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ExpressionEvaluator
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * ExpressionEvaluatorFactory::class => [
 *      'expressionEvaluatorName1' => [
 *          'class' => ExpressionEvaluatorFactory::class, // default value
 *          TODO: add expression function providers
 *          'pluginManagers' => [
 *              'pluginManagerServiceName1' => [
 *                  'class' => FilterPluginManager::class,
 *                  'calledMethod' => 'filter,
 *                  'pluginServices' => [
 *                      'digits',
 *                      'rqlReplace',
 *                      //...
 *                  ]
 *              ]
 *              'pluginManagerServiceName2' => [
 *                  //...
 *              ]
 *          ]
 *      ],
 *      'expressionEvaluatorName2' => [
 *          //...
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class ExpressionEvaluatorFactory implements AbstractFactoryInterface
{
    /**
     * Parent class for plugin. By default doesn't set
     */
    const DEFAULT_CLASS = ExpressionEvaluator::class;

    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * Config for plugin managers
     */
    const PLUGIN_MANAGERS_KEY = 'pluginManagers';

    /**
     * Config for plugin manager called method
     */
    const PLUGIN_CALLED_METHOD_KEY = 'calledMethod';

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

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ExpressionEvaluator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $container->get('config')[self::class][$requestedName];
        $class = $this->getClass($serviceConfig);

        /** @var ExpressionEvaluator $expressionEvaluator */
        $expressionEvaluator = new $class();

        if (!isset($serviceConfig[self::PLUGIN_MANAGERS_KEY]) || !is_array($serviceConfig[self::PLUGIN_MANAGERS_KEY])) {
            throw new InvalidArgumentException("Missing 'pluginManagers' option in config");
        }

        $pluginManagers = $serviceConfig[self::PLUGIN_MANAGERS_KEY];

        foreach ($pluginManagers as $pluginManagerService => $config) {
            $pluginManager = $this->getPluginManager($container, $pluginManagerService, $config);
            $pluginFunctions = $this->getPluginFunctions($config);
            $calledMethod = $this->getCalledMethod($config);

            $expressionEvaluator->registerPluginFunctions($pluginManager, $pluginFunctions, $calledMethod);
        }

        return new $class();
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getPluginFunctions($config)
    {
        if (isset($config[self::PLUGIN_SERVICES_KEY]) && is_array($config[self::PLUGIN_SERVICES_KEY])) {
            return $config[self::PLUGIN_SERVICES_KEY];
        }

        throw new InvalidArgumentException("Invalid plugin functions config");
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getCalledMethod($config)
    {
        if (isset($config[self::PLUGIN_CALLED_METHOD_KEY]) && is_string($config[self::PLUGIN_CALLED_METHOD_KEY])) {
            return $config[self::PLUGIN_CALLED_METHOD_KEY];
        }

        throw new InvalidArgumentException("Invalid called me config");
    }

    /**
     * @param ContainerInterface $container
     * @param $pluginManagerService
     * @param array $config
     * @return mixed
     */
    public function getPluginManager(ContainerInterface $container, $pluginManagerService, array $config)
    {
        if ($container->has($pluginManagerService)) {
            return $container->get($pluginManagerService);
        }

        if (isset($config[self::CLASS_KEY]) && $container->has($config[self::CLASS_KEY])) {
            return $container->get($config[self::CLASS_KEY]);
        }

        throw new InvalidArgumentException(
            "Can't create plugin manager by '$pluginManagerService' config key"
        );
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
