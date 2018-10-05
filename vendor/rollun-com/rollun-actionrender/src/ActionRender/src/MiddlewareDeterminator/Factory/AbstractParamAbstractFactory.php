<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:26 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\MiddlewareDeterminator\AbstractParam;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

abstract class AbstractParamAbstractFactory extends AbstractMiddlewareDeterminatorAbstractFactory
{
    const KEY_NAME = "name";

    const KEY_DEFAULT_VALUE = "defaultValue";

    protected $instanceOf = AbstractParam::class;

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $serviceConfig = $config[static::KEY][$requestedName];
        $class = $serviceConfig[static::KEY_CLASS];//TODO: maybe check constructor compare...
        $name = $serviceConfig[static::KEY_NAME];
        $defaultValue = isset($serviceConfig[static::KEY_DEFAULT_VALUE]) ? $serviceConfig[static::KEY_DEFAULT_VALUE] : null;
        return new $class($name, $defaultValue);
    }
}