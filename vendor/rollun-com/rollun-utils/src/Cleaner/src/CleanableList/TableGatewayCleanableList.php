<?php


namespace rollun\cleaner\CleanableList;


use Traversable;
use rollun\tableGateway\TableGatewayIterator;
use rollun\utils\Cleaner\CleanableList\CleanableListInterface;
use Zend\Db\TableGateway\TableGateway;


class TableGatewayCleanableList implements CleanableListInterface, \IteratorAggregate
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
     * TableGatewayCleanableList constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->primaryKeyName = "id";
    }

    /**
     * @param $item
     */
    public function deleteItem($item)
    {
        $this->tableGateway->delete([$this->primaryKeyName => $item[$this->primaryKeyName]]);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new TableGatewayIterator($this->tableGateway, $this->primaryKeyName);
    }
}