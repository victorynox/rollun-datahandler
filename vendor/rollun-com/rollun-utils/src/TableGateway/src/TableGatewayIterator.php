<?php


namespace rollun\tableGateway;


use Traversable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Source\MysqlMetadata;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayIterator implements \IteratorAggregate
{
    /**
     * @var TableGateway
     */
    private $tableGateway;

    /**
     * @var string
     */
    private $primaryKeyName;

    /**
     * TableGatewayIterator constructor.
     * @param TableGateway $tableGateway
     * @param string $primaryKeyName
     */
    public function __construct(TableGateway $tableGateway, string $primaryKeyName = "id")
    {
        $this->tableGateway = $tableGateway;
        $this->primaryKeyName = $primaryKeyName;
    }

    /**
     * @param string $primaryKey
     * @param int $limit
     * @return array
     */
    private function selectRow($primaryKey = null, $limit = 10)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->limit($limit);
        $select->order($this->primaryKeyName . ' ' . Select::ORDER_ASCENDING);
        $select->columns(['*']);
        if (isset($primaryKey)) {
            $select->where("(".
                "{$this->tableGateway->getAdapter()->getPlatform()->quoteIdentifier($this->primaryKeyName)}" .
                ">" .
                (is_numeric($primaryKey) ? "$primaryKey" : "{$this->tableGateway->getAdapter()->getPlatform()->quoteValue($primaryKey)}") .
            ")");
        }

        //build sql string
        $sql = $this->tableGateway->getSql()->buildSqlString($select);

        /** @var Adapter $adapter */
        $adapter = $this->tableGateway->getAdapter();
        $rowset = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);

        return $rowset->toArray();
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @param int $limit
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator($limit = 10)
    {
        $hasNext = true;
        $primaryKey = null;
        while ($hasNext) {
            $rows = $this->selectRow($primaryKey, $limit);
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    yield $row;
                }
                $primaryKey = end($rows)[$this->primaryKeyName];
            } else {
                $hasNext = false;
            }
        }
    }
}