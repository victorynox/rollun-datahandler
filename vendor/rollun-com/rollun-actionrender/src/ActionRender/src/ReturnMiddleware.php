<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.01.17
 * Time: 12:39
 */

namespace rollun\actionrender;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReturnMiddleware implements MiddlewareInterface
{
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
        $response = $request->getAttribute(Response::class);
        if(is_null($response)) {
            $response = $delegate->process($request);
        }
        return $response;
    }
}
