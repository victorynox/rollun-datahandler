<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:12 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;


use Interop\Container\ContainerInterface;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AbstractMiddlewareDeterminatorAbstractFactory
 * @package rollun\actionrender\MiddlewareDeterminator\Factory
 */
abstract class AbstractMiddlewareDeterminatorAbstractFactory implements AbstractFactoryInterface
{
    const KEY = AbstractMiddlewareDeterminatorAbstractFactory::class;

    const KEY_CLASS = "class";

    /**
     * Return service instanceof
     * @var string
     */
    protected $instanceOf = MiddlewareDeterminatorInterface::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return (
            isset($config[static::KEY][$requestedName]) &&
            is_a($config[static::KEY][$requestedName][static::KEY_CLASS], $this->instanceOf, true)
        );
    }

}