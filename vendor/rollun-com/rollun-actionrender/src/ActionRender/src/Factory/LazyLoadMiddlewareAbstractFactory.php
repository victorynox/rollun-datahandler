<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:27
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\LazyLoadMiddleware;
use rollun\actionrender\MiddlewarePluginManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class LazyLoadMiddlewareAbstractFactory implements AbstractFactoryInterface
{
    const KEY = LazyLoadMiddlewareAbstractFactory::class;

    const KEY_MIDDLEWARE_DETERMINATOR = 'middlewareDeterminator';

    const KEY_MIDDLEWARE_PLUGIN_MANAGER = 'middlewarePluginManager';

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        return isset($config[static::KEY][$requestedName]);
    }

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
        $config = $container->get('config');
        $serviceConfig = $config[static::KEY][$requestedName];
        $middlewareDeterminator = $container->get($serviceConfig[static::KEY_MIDDLEWARE_DETERMINATOR]);
        if(isset($serviceConfig[static::KEY_MIDDLEWARE_PLUGIN_MANAGER])) {
            $middlewarePluginManager = $container->get($serviceConfig[static::KEY_MIDDLEWARE_PLUGIN_MANAGER]);
        } else {
            $middlewarePluginManager = $container->get(MiddlewarePluginManager::class);
        }
        return new LazyLoadMiddleware($middlewareDeterminator, $middlewarePluginManager);
    }
}
