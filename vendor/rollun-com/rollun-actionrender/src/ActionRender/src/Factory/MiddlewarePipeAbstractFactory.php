<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 15:26
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\MiddlewarePipeAbstract;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\Middleware\CallableMiddlewareWrapperFactory;
use Zend\Stratigility\MiddlewarePipe;

class MiddlewarePipeAbstractFactory implements AbstractFactoryInterface
{
    const KEY = MiddlewarePipeAbstractFactory::class;

    const KEY_MIDDLEWARES = 'middlewares';

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
        $returnMiddlewares = [];
        $config = $container->get('config');
        $middlewares = $config[static::KEY][$requestedName][static::KEY_MIDDLEWARES];
        foreach ($middlewares as $key => $middleware) {
            if ($container->has($middleware)) {
                $returnMiddlewares[$key] = $container->get($middleware);
            } else {
                throw new ServiceNotFoundException("$middleware not found in Container");
            }
        }

        ksort($returnMiddlewares);
        return new MiddlewarePipeAbstract($returnMiddlewares);
    }

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
            return true;
        }
        return false;
    }
}
