<?php

namespace rollun\test\utils\TableGateway;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\Assert;
use rollun\tableGateway\TableGatewayIterator;
use PHPUnit\Framework\TestCase;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayIteratorTest extends TestCase
{
    /** @var ContainerInterface */
    private $container;

    /** @var Adapter */
    private $adapter;

    /** @var TableGateway */
    private $tableGateway;


    public function setUp()
    {
        $this->container = include './config/container.php';
        $this->adapter = $this->container->get('db');

        $tableName = "tbl_iterable";
        $dropTableStr = "DROP TABLE IF EXISTS " . $this->adapter->getPlatform()->quoteIdentifier($tableName);
        $deleteStatement = $this->adapter->query($dropTableStr);
        $deleteStatement->execute();

        $createStatementStr = 'CREATE TABLE IF NOT EXISTS '.$tableName.' (id INT, surname VARCHAR(255))';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();

        $this->tableGateway = $this->container->get($tableName);
    }

    public function testIterator() {
        $expectedItems = [];
        for ($i = 0; $i < 10; $i++) {
            $item = [
                "id" => $i,
                "surname" => "asd$i"
            ];
            $this->tableGateway->insert($item);
            $expectedItems[] = $item;
        }
        $data = new TableGatewayIterator($this->tableGateway);
        foreach ($data as $item) {
            Assert::assertTrue(in_array($item, $expectedItems), "item :" . print_r($item, true) . " not found in expected items");
        }
    }
}
