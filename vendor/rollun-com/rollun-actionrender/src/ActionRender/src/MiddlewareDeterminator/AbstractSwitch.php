<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:02 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use Psr\Http\Message\ServerRequestInterface;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;

/**
 * Class SwitchAbstract
 * @package rollun\actionrender\MiddlewareDeterminator
 */
abstract class AbstractSwitch implements MiddlewareDeterminatorInterface
{
    /**
     * [
     *  $key //-> pattern
     *      => $value, //-> middlewareServiceName
     * ]
     * @var array
     */
    protected $middlewaresMatching;

    /**
     * @var string
     */
    protected $name;

    /**
     * SwitchGetterLazyLoad constructor.
     * @param array $middlewaresMatching
     * @param $name
     */
    public function __construct(array $middlewaresMatching, $name)
    {
        $this->middlewaresMatching= $middlewaresMatching;
        $this->name = $name;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    abstract function getSwitchValue(ServerRequestInterface $request);

    public function getMiddlewareServiceName(ServerRequestInterface $request)
    {
        $value = $this->getSwitchValue($request);
        if (is_null($value)) {
            throw new MiddlewareDeterminatorException("Not found value for switch name: {$this->name}.");
        }
        foreach ($this->middlewaresMatching as $pattern => $middlewareServiceName) {
            if (preg_match($pattern, $value)) {
                return $middlewareServiceName;
            }
        }
        throw new MiddlewareDeterminatorException("Middleware service name for switch name: {$this->name} with $value not determinate.");
    }
}