<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:59
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use Psr\Http\Message\ServerRequestInterface as Request;

class AttributeParam extends AbstractParam
{
    /**
     * Return
     * @param Request $request
     * @return string
     */
    protected function getValue(Request $request)
    {
        return $request->getAttribute($this->name);
    }
}
