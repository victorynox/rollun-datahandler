<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:14
 */

namespace rollun\actionrender;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;

/**
 *
 * Class LazyLoadMiddleware
 * @package rollun\actionrender
 */
class LazyLoadMiddleware implements MiddlewareInterface
{
    /**
     * @var MiddlewareDeterminatorInterface
     */
    private $middlewareDeterminator;

    /**
     * @var MiddlewarePluginManager
     */
    private $middlewarePluginManager;

    /**
     * DynamicPipe constructor.
     * @param MiddlewareDeterminatorInterface $middlewareDeterminator
     * @param MiddlewarePluginManager $middlewarePluginManager
     */
    public function __construct(MiddlewareDeterminatorInterface $middlewareDeterminator, MiddlewarePluginManager $middlewarePluginManager)
    {
        $this->middlewareDeterminator = $middlewareDeterminator;
        $this->middlewarePluginManager = $middlewarePluginManager;
    }

    /**
     * @param Request $request
     * @param DelegateInterface $delegate
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        $middlewareServiceName = $this->middlewareDeterminator->getMiddlewareServiceName($request);
        /** @var MiddlewareInterface $middleware */
        $middleware = $this->middlewarePluginManager->get($middlewareServiceName);
        return $middleware->process($request, $delegate);
    }
}
