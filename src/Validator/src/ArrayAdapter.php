<?php

namespace rollun\datahandler\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception\InvalidArgumentException;
use Zend\Validator\ValidatorInterface;

/**
 * Class ArrayAdapter
 * @package rollun\datahandler\Validator
 */
class ArrayAdapter extends AbstractValidator
{
    /**
     * @var array
     */
    protected $columnsToValidate;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * ArrayAdapter constructor.
     * @param ValidatorInterface $validator
     * @param null $options
     */
    public function __construct(ValidatorInterface $validator, $options = null)
    {
        parent::__construct($options);
        $this->validator = $validator;
    }

    /**
     * @param $columnsToValidate
     */
    public function setColumnsToValidate($columnsToValidate)
    {
        if (is_array($columnsToValidate)) {
            $this->columnsToValidate = $columnsToValidate;
        } elseif (is_string($columnsToValidate)) {
            $this->columnsToValidate = [$columnsToValidate];
        } else {
            throw new InvalidArgumentException("Invalid option 'columnsToValidate'");
        }
    }

    /**
     * @return array
     */
    public function getColumnsToValidate()
    {
        if ($this->columnsToValidate === null) {
            throw new InvalidArgumentException("Missing 'columnsToValidate' option");
        }

        return $this->columnsToValidate;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException("Incoming value must be an array");
        }

        // Create copy of columnsToValidate
        $columnsToValidate = $this->getColumnsToValidate();

        foreach ($columnsToValidate as $column) {
            if (!isset($value[$column])) {
                throw new InvalidArgumentException("{$column} doesn't exist in incoming value");
            }
        }

        $valueColumns = $this->getValueColumns($value);

        foreach ($valueColumns as $column) {
            $isValid = $this->validator->isValid($column);

            if (!$isValid) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $value
     * @return array
     */
    protected function getValueColumns(array $value)
    {
        $valueColumns = [];
        $columnsToValidate = $this->getColumnsToValidate();

        foreach ($columnsToValidate as $column) {
            $valueColumns[] = $value[$column];
        }

        return $valueColumns;
    }
}
