<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 5:05 PM
 */

namespace rollun\actionrender\Renderer;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

/**
 * Class AbstractRender
 * @package rollun\actionrender\Renderer
 */
abstract class AbstractRenderer implements MiddlewareInterface
{
    const RESPONSE_DATA = "responseData";
}