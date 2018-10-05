<?php


namespace rollun\logger\Cleaner\Validators;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use rollun\utils\Cleaner\CleaningValidator\Factory\AbstractCleaningValidatorAbstractFactory;
use RuntimeException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class LevelValidatorAbstractFactory extends AbstractCleaningValidatorAbstractFactory
{
    const DEFAULT_CLASS = LevelValidator::class;

    const KEY_LEVEL = "level";

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
        $serviceFactory = $this->getServiceConfig($container,  $requestedName);

        return new LevelValidator($serviceFactory[static::KEY_LEVEL]);
    }
}