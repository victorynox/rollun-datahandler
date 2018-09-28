<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:17 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;


use Interop\Container\ContainerInterface;
use rollun\actionrender\MiddlewareDeterminator\AbstractSwitch;
/**
 * Class AbstractSwitchAbstractFactory
 * @package rollun\actionrender\MiddlewareDeterminator\Factory
 */
abstract class AbstractSwitchAbstractFactory extends AbstractMiddlewareDeterminatorAbstractFactory
{
    const KEY_MIDDLEWARE_MATCHING = "middlewareMatching";

    const KEY_NAME = "name";

    protected $instanceOf = AbstractSwitch::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $serviceConfig = $config[static::KEY][$requestedName];
        $class = $serviceConfig[static::KEY_CLASS];//TODO: maybe add check construct compatibility...
        $middlewareMatching = $serviceConfig[static::KEY_MIDDLEWARE_MATCHING];
        $name = $serviceConfig[static::KEY_NAME];
        return new $class($middlewareMatching, $name);
    }


}