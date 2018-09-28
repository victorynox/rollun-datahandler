<?php


namespace rollun\utils\Cleaner\CleaningValidator\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AbstractCleaningValidatorAbstractFactory
 * @package rollun\utils\Cleaner\CleaningValidator\Factory
 */
abstract class AbstractCleaningValidatorAbstractFactory implements AbstractFactoryInterface
{

    const KEY = AbstractCleaningValidatorAbstractFactory::class;

    const KEY_CLASS = "class";

    const DEFAULT_CLASS = CleaningValidatorInterface::class;


    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return (
            isset($config[static::KEY][$requestedName][static::KEY_CLASS])
            && is_a($config[static::KEY][$requestedName][static::KEY_CLASS], static::DEFAULT_CLASS, true)
        );
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return
     */
    protected function getServiceConfig(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return $config[static::KEY][$requestedName];
    }
}