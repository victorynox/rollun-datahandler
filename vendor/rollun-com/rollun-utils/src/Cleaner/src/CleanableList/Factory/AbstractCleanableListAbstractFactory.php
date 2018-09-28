<?php


namespace rollun\utils\Cleaner\CleanableList\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\utils\Cleaner\CleanableList\CleanableListInterface;
use rollun\utils\Factory\AbstractAbstractFactory;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class AbstractCleanableListAbstractFactory
 * @package rollun\utils\Cleaner\CleanableList\Factory
 */
abstract class AbstractCleanableListAbstractFactory extends AbstractAbstractFactory
{
    const KEY = AbstractCleanableListAbstractFactory::class;

    const DEFAULT_CLASS = CleanableListInterface::class;

}