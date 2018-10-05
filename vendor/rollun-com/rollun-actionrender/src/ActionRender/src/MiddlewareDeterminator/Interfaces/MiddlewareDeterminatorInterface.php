<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:20
 */

namespace rollun\actionrender\MiddlewareDeterminator\Interfaces;

use Psr\Http\Message\ServerRequestInterface as Request;

interface MiddlewareDeterminatorInterface
{
    /**
     * Return middleware service name
     * @param Request $request
     * @return string
     */
    public function getMiddlewareServiceName(Request $request);
}
