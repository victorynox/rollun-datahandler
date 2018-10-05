<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 12:03
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\ActionRenderMiddleware;
use rollun\actionrender\ReturnMiddleware;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ActionRenderAbstractFactory implements AbstractFactoryInterface
{
    const KEY = ActionRenderAbstractFactory::class;

    const KEY_ACTION_MIDDLEWARE_SERVICE = 'action_middleware_service';

    const KEY_RENDER_MIDDLEWARE_SERVICE = 'render_middleware_service';

    const KEY_RETURNER_MIDDLEWARE_SERVICE = 'returner_middleware_service';

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
        if (isset($config[static::KEY][$requestedName])) {
            $middleware = $config[static::KEY][$requestedName];
            return (
                isset($middleware[static::KEY_ACTION_MIDDLEWARE_SERVICE]) &&
                isset($middleware[static::KEY_RENDER_MIDDLEWARE_SERVICE])
            );
        }
        return false;
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
        $factoryConfig = $config[static::KEY][$requestedName];
        $action = $factoryConfig[static::KEY_ACTION_MIDDLEWARE_SERVICE];
        $render = $factoryConfig[static::KEY_RENDER_MIDDLEWARE_SERVICE];
        $returner = isset($factoryConfig[static::KEY_RETURNER_MIDDLEWARE_SERVICE]) ?
            $factoryConfig[static::KEY_RETURNER_MIDDLEWARE_SERVICE] : null;

        if ($container->has($action) && $container->has($render)) {
            if (!is_null($returner)) {
                if ($container->has($returner)) {
                    return new ActionRenderMiddleware(
                        $container->get($action),
                        $container->get($render),
                        $container->get($returner)
                    );
                }
                throw new ServiceNotCreatedException("Not found $returner for service");
            }
            return new ActionRenderMiddleware(
                $container->get($action),
                $container->get($render),
                new ReturnMiddleware()
            );
        }
        $errorStr = "Not found ";
        $errorStr .= !$container->has($action) ? $action . " " : "";
        $errorStr .= !$container->has($render) ? $render . " " : "";
        throw new ServiceNotCreatedException($errorStr . "for service");
    }
}
