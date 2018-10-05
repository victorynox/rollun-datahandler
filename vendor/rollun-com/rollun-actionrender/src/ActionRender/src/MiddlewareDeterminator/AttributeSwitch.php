<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 13:15
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;

class AttributeSwitch extends AbstractSwitch
{
    /**
     * @param Request $request
     * @return string
     */
    function getSwitchValue(Request $request)
    {
        return $request->getAttribute($this->name);
    }
}
