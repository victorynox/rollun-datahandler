<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 18:11
 */

namespace rollun\actionrender\Renderer\Html;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouteResult;

class HtmlParamResolver implements MiddlewareInterface
{
    const KEY_ATTRIBUTE_TEMPLATE_NAME = 'templateName';

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param DelegateInterface $delegate
     *
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if($request->getAttribute(static::KEY_ATTRIBUTE_TEMPLATE_NAME) === null){
            if(!isset($routeResult)) {
                throw new \RuntimeException(RouteResult::class . " not found in request attribute.");
            }
            $routeName = $routeResult->getMatchedRouteName();
            $routeNamePart = explode("-", $routeName, 2);
            if(count($routeNamePart) == 1) {
                $templateNamespace = "app";
                $templateName = "$routeName";
            } else {
                $templateNamespace = $routeNamePart[0];
                $templateName = $routeNamePart[1];
            }
            $routeName = "$templateNamespace::$templateName";
            $request = $request->withAttribute(static::KEY_ATTRIBUTE_TEMPLATE_NAME, $routeName);
        };

        $response = $delegate->process($request);
        return $response;
    }
}
