<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:22 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;

use rollun\actionrender\MiddlewareDeterminator\AttributeSwitch;

/**
 * Class AttributeSwitchAbstractFactory
 * @package rollun\actionrender\MiddlewareDeterminator\Factory
 */
class AttributeSwitchAbstractFactory extends AbstractSwitchAbstractFactory
{
    protected $instanceOf = AttributeSwitch::class;
}