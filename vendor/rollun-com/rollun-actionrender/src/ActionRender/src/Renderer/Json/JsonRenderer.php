<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 18:06
 */

namespace rollun\actionrender\Renderer\Json;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\Renderer\AbstractRenderer;
use Zend\Diactoros\Response\JsonResponse;

class JsonRenderer extends AbstractRenderer
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
        $data = $request->getAttribute(static::RESPONSE_DATA);

        /** @var Response $response */
        $response = $request->getAttribute(Response::class) ?: null;
        if (!isset($response)) {
            $status = 200;
            $headers = [];
        } else {
            $status = $response->getStatusCode();
            $headers = $response->getHeaders();
        }

        $response = new JsonResponse($data, $status);
        foreach ($headers as $header => $value) {
            $response = $response->withHeader($header, $value);
        }
        $request = $request->withAttribute(
            Response::class,
            $response
        );
        $response = $delegate->process($request);
        return $response;
    }
}
