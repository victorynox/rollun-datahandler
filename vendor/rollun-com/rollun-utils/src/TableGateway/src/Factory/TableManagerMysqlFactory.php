<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\tableGateway\Factory;

use Interop\Container\ContainerInterface;
use rollun\tableGateway\TableManagerMysql;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Create and return an instance of the TableManagerMysql
 *
 * Return TableManagerMysql
 *
 * Requre service with name 'db' - db adapter
 *
 * @uses zend-db
 * @see https://github.com/zendframework/zend-db
 * @category   rest
 * @package    zaboy
 */
class TableManagerMysqlFactory implements FactoryInterface
{
    /**
     * Create and return an instance of the TableGateway.
     *
     * 'use Zend\ServiceManager\AbstractFactoryInterface;' for V2 to
     * 'use Zend\ServiceManager\Factory\AbstractFactoryInterface;' for V3
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  array $options
     * @return mixed|TableManagerMysql
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $db = $container->get('db');
        $config = $container->get("config");
        if (!isset($config[TableManagerMysql::KEY_IN_CONFIG])) {
            //throw new RestException('There is not "tableManager" key in config');
            $tableManagerConfig = [
                TableManagerMysql::KEY_AUTOCREATE_TABLES => [],
                TableManagerMysql::KEY_TABLES_CONFIGS => []
            ];
        } else {
            $tableManagerConfig = $config[TableManagerMysql::KEY_IN_CONFIG];
        }

        $tableManager = new TableManagerMysql($db, $tableManagerConfig);
        return $tableManager;
    }
}
