<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:31 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractParam implements MiddlewareDeterminatorInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var null
     */
    private $defaultValue;

    /**
     * Attribute constructor.
     * @param string $name
     * @param string $defaultValue
     */
    public function __construct($name, $defaultValue = null)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Return
     * @param Request $request
     * @return string
     */
    abstract protected function getValue(Request $request);

    /**
     * @param Request $request
     * @return string
     */
    public function getMiddlewareServiceName(Request $request)
    {
        $serviceName = $this->getValue($request);
        return $serviceName ?: $this->defaultValue;
    }
}