<?php

namespace rollun\datanadler\Processor;

use Zend\Filter\FilterInterface;
use Zend\Filter\FilterPluginManager;
use Zend\Validator\ValidatorInterface;

/**
 * Class FilterApplier
 * @package rollun\datanadler\Processor
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
     * @param FilterPluginManager $filterPluginManager
     * @param array $option
     * @param ValidatorInterface|null $validator
     *
     * Valid keys are:
     * - columnToRead - string, data store valid column
     * - columnToWrite - string, data store valid column
     * - filters - array of FilterInterface object
     *
     */
    public function __construct(
        FilterPluginManager $filterPluginManager,
        $option = [],
        ValidatorInterface $validator = null
    ) {
        parent::__construct($option, $validator);

        $this->setOptions($option);
        $this->setFilterPluginManager($filterPluginManager);
    }

    /**
     * @param string $columnToRead
     */
    public function setColumnToRead(string $columnToRead)
    {
        $this->columnToRead = $columnToRead;
    }

    public function getColumnToRead()
    {
        if (!isset($this->columnToRead) || !isset($value[$this->columnToRead])) {
            throw new \InvalidArgumentException(self::class . ' processor: column to read is not set or valid');
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
     * @param $filterPluginManager FilterPluginManager
     */
    public function setFilterPluginManager(FilterPluginManager $filterPluginManager)
    {
        $this->filterPluginManager = $filterPluginManager;
    }

    /**
     * @return FilterPluginManager
     */
    public function getFilterPluginManager()
    {
        if (!isset($this->filterPluginManager)) {
            throw new \InvalidArgumentException(self::class . ' processor: filter plugin manager is not set');
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
        $value = $value[$columnToRead];
        $filters = $this->filters;
        ksort($filters);

        foreach ($filters as $filter) {
            $filterService = $this->buildFilter($filter);
            $value = $filterService->filter($value);
        }

        $columnToWrite = $this->columnToWrite ?? $columnToRead;
        $value[$columnToWrite] = $value;

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
            throw new \InvalidArgumentException(self::class . ' processor: filter service name is not set');
        }

        $service = $this->getFilterPluginManager()->build($filter['service'], $filter['options'] ?? null);

        return $service;
    }
}
