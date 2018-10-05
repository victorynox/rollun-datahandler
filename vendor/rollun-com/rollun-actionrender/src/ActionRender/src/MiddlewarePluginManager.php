<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:39
 */

namespace rollun\actionrender;

use Interop\Container\ContainerInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * Class MiddlewarePluginManager
 * @package rollun\actionrender
 * Service plugin manager return middleware service.
 */
class MiddlewarePluginManager extends AbstractPluginManager
{
    protected $instanceOf = MiddlewareInterface::class;
}
