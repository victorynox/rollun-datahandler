<?php

namespace rollun\datahandler\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class PluginAbstractFactoryAbstract
 * @package rollun\datahandler\Factory
 */
abstract class PluginAbstractFactoryAbstract implements AbstractFactoryInterface
{
    /**
     * Parent class for plugin
     */
    const DEFAULT_CLASS = null;

    /**
     * Common namespace name for plugin config
     */
    const KEY = null;

    /**
     * Config key for abstract factories configs
     */
    const ABSTRACT_FACTORY_CONFIG = 'abstract_factory_config';

    /**
     * Config key for plugin options
     */
    const OPTIONS_KEY = 'options';

    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return !is_null($this->getServiceConfig($container, $requestedName));
    }

    /**
     * Get options for plugin (merged service config and options passed through __invoke)
     *
     * @param $serviceConfig
     * @param array|null $options
     * @return array
     */
    public function getPluginOptions($serviceConfig, array $options = null)
    {
        $pluginOptions = [];

        if (isset($options) && is_array($options)) {
            $pluginOptions = $options;
        }

        if (isset($serviceConfig[self::OPTIONS_KEY]) && is_array($serviceConfig[self::OPTIONS_KEY])) {
            $intersect = array_intersect(array_keys($pluginOptions), array_keys($serviceConfig[self::OPTIONS_KEY]));

            if (!empty($intersect)) {
                $columns = implode(', ', $intersect);

                throw new \LogicException(
                    'Can\'t merge config with options. [' . $columns . '] columns already set in config'
                );
            }

            $pluginOptions = array_merge($pluginOptions, $serviceConfig[self::OPTIONS_KEY]);
        }

        return $pluginOptions;
    }

    /**
     * Get caused class
     *
     * @param array $serviceConfig
     * @param bool $required
     * @return mixed
     */
    public function getClass(array $serviceConfig, $required = false)
    {
        // TODO: add test on default class
        if (!isset($serviceConfig[self::CLASS_KEY])) {
            if (!$required) {
                return static::DEFAULT_CLASS;
            }

            throw new \InvalidArgumentException("There is no 'class' config for plugin in config");
        } else if (!is_a($serviceConfig[self::CLASS_KEY], static::DEFAULT_CLASS, true)) {
            throw new \InvalidArgumentException(
                'Caused class must implement or extend ' . static::DEFAULT_CLASS
            );
        }

        return $serviceConfig[self::CLASS_KEY];
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return null|array
     */
    public function getServiceConfig(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        return $config[static::KEY][self::ABSTRACT_FACTORY_CONFIG][static::class][$requestedName] ?? null;
    }
}
