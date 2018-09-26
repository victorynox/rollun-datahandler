<?php

namespace rollun\datahandler\Processor;

use InvalidArgumentException;
use Zend\Filter\FilterInterface;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\ValidatorInterface;

/**
 * Class FilterApplier
 * @package rollun\datahandler\Processor
 */
class FilterApplier extends AbstractProcessor
{
    const FILTER_PROCESSOR_SERVICE_PREFIX = 'filterProcess_';

    /**
     * Data store column which need to filter
     *
     * @var string
     */
    protected $columnToRead;

    /**
     * Data store column in which need to write filtered value
     *
     * @var string
     */
    protected $columnToWrite;

    /**
     * Array of filters
     *
     * Example of filter`s array. Array`s indexes are priorities of filters
     *
     *  [
     *      0 => [
     *          'service' => 'rql' - filter service
     *          'options' => [
     *              'pattern' => '*Some*pattern*'
     *              'replacement' => 'Concrete string'
     *          ]
     *      ]
     *  ]
     *
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var FilterPluginManager
     */
    protected $filterPluginManager;

    /**
     * FilterApplier constructor.
     * @param array $option
     * @param ValidatorInterface|null $validator
     * @param FilterPluginManager $filterPluginManager
     *
     * Valid keys are:
     * - columnToRead - string, data store valid column
     * - columnToWrite - string, data store valid column
     * - filters - array of FilterInterface object
     *
     */
    public function __construct(
        $option = null,
        ValidatorInterface $validator = null,
        FilterPluginManager $filterPluginManager = null
    ) {
        parent::__construct($option, $validator);

        $this->filterPluginManager = $filterPluginManager;
    }

    /**
     * @param string $columnToRead
     */
    public function setColumnToRead(string $columnToRead)
    {
        $this->columnToRead = $columnToRead;
    }

    /**
     * @return string
     */
    public function getColumnToRead()
    {
        if (!isset($this->columnToRead)) {
            throw new InvalidArgumentException("Missing option 'columnToRaed'");
        }

        return $this->columnToRead;
    }

    /**
     * @param string $columnToWrite
     */
    public function setColumnToWrite(string $columnToWrite)
    {
        $this->columnToWrite = $columnToWrite;
    }

    /**
     * @param FilterInterface[] $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return FilterPluginManager
     */
    public function getFilterPluginManager()
    {
        if ($this->filterPluginManager === null) {
            $this->filterPluginManager = new FilterPluginManager(new ServiceManager());
        }

        return $this->filterPluginManager;
    }

    /**
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function doProcess(array $value)
    {
        $columnToRead = $this->getColumnToRead();

        if (!isset($value[$columnToRead])) {
            throw new InvalidArgumentException("Column '{$columnToRead}' does'nt exist in incoming value");
        }

        $columnValue = $value[$columnToRead];
        $filters = $this->filters;
        ksort($filters);

        foreach ($filters as $filter) {
            $filterService = $this->buildFilter($filter);
            $columnValue = $filterService->filter($columnValue);
        }

        $columnToWrite = $this->columnToWrite ?? $columnToRead;
        $value[$columnToWrite] = $columnValue;

        return $value;
    }

    /**
     * @param $filter
     * @return FilterInterface
     * @throws \Exception
     */
    protected function buildFilter($filter)
    {
        if (!isset($filter['service'])) {
            throw new InvalidArgumentException("Missing 'service' column in 'filters' option");
        }

        $service = $this->getFilterPluginManager()->build($filter['service'], $filter['options'] ?? null);

        return $service;
    }
}
