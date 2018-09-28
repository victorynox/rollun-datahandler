<?php


namespace rollun\utils\Factory;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AbstractAbstractFactory
 * @package rollun\utils\Factory
 */
abstract class AbstractAbstractFactory implements AbstractFactoryInterface
{
    /**
     * need to override
     * Factory config key
     */
    const KEY = null;

    /**
     * Factory child class name
     * need to override
     */
    const DEFAULT_CLASS = null;

    const KEY_CLASS = "class";

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return (
            isset($config[static::KEY][$requestedName][static::KEY_CLASS])
            && is_a($config[static::KEY][$requestedName][static::KEY_CLASS], static::DEFAULT_CLASS, true)
        );
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return
     */
    protected function getServiceConfig(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return $config[static::KEY][$requestedName];
    }
}