<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:38
 */

namespace rollun\actionrender;

use Interop\Container\Exception\NotFoundException;
use rollun\logger\Exception\LoggedException;

class ServiceNotFoundException extends \RuntimeException implements NotFoundException
{

}
