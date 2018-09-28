<?php


namespace rollun\utils\Cleaner\CleanableList\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\cleaner\CleanableList\TableGatewayCleanableList;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class TableGatewayCleanableListAbstractFactory extends AbstractCleanableListAbstractFactory
{
    const KEY = TableGatewayCleanableListAbstractFactory::class;

    const KEY_TABLE_GATEWAY = "tableGateway";

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
        return new TableGatewayCleanableList($serviceConfig[static::KEY_TABLE_GATEWAY]);
    }
}