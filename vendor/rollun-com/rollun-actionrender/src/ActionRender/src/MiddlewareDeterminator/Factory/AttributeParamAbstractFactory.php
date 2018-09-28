<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 22.03.18
 * Time: 3:22 PM
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;

use rollun\actionrender\MiddlewareDeterminator\AttributeParam;

/**
 * Class HeaderSwitchAbstractFactory
 * @package rollun\actionrender\MiddlewareDeterminator\Factory
 */
class AttributeParamAbstractFactory extends AbstractParamAbstractFactory
{
    protected $instanceOf = AttributeParam::class;
}