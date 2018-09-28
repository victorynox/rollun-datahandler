<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:22 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;

use rollun\actionrender\MiddlewareDeterminator\AttributeSwitch;
use rollun\actionrender\MiddlewareDeterminator\HeaderSwitch;

/**
 * Class HeaderSwitchAbstractFactory
 * @package rollun\actionrender\MiddlewareDeterminator\Factory
 */
class HeaderSwitchAbstractFactory extends AbstractSwitchAbstractFactory
{
    protected $instanceOf = HeaderSwitch::class;
}