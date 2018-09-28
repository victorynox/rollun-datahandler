<?php


namespace rollun\utils\Cleaner\CleaningValidator\Aggregator\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\utils\Cleaner\CleaningValidator\Factory\AbstractCleaningValidatorAbstractFactory;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class AggregatorAbstractFactory extends AbstractCleaningValidatorAbstractFactory
{
    const KEY_VALIDATORS = "validators";

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
        $serviceConfig = $this->canCreate($container, $requestedName);
        $validatorsServiceName = $serviceConfig[static::KEY_VALIDATORS];
        $validators = [];
        foreach ($validatorsServiceName as $validatorName) {
            $validators[] = $container->get($validatorName);
        }
        $class = $serviceConfig[static::KEY_CLASS];
        return new $class($validators);
    }
}