<?php


namespace rollun\utils\Cleaner\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\utils\Cleaner\Cleaner;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class CleanerAbstractFactory implements AbstractFactoryInterface
{

    const KEY = CleanerAbstractFactory::class;

    const KEY_CLASS = "class";

    const DEFAULT_CLASS = Cleaner::class;

    const KEY_CLEANABLE_LIST = "cleanableList";

    const KEY_CLEANABLE_VALIDATOR = "cleanableValidator";

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

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $cleanableList = $container->get($serviceConfig[static::KEY_CLEANABLE_LIST]);
        $cleanableValidator = $container->get($serviceConfig[static::KEY_CLEANABLE_VALIDATOR]);
        return new Cleaner($cleanableList, $cleanableValidator);
    }
}