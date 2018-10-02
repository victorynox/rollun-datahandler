<?php

namespace rollun\datahandler\Validator;

use InvalidArgumentException;
use Zend\Validator\AbstractValidator;

/**
 * Class IsColumnExist
 * @package rollun\datahandler\Validator
 */
class IsColumnExist extends AbstractValidator
{
    /**
     * @var string
     */
    protected $validateColumn;

    /**
     * IsColumnExist constructor.
     *
     * Valid $option keys are:
     * - validateColumn - column that will be checked on existing in array
     *
     * @param null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function getValidateColumn()
    {
        if ($this->validateColumn === null) {
            throw new InvalidArgumentException("Missing 'validateColumn' option");
        }

        return $this->validateColumn;
    }

    /**
     * @param string $validateColumn
     */
    public function setValidateColumn($validateColumn)
    {
        if (is_array($validateColumn)) {
            $this->validateColumn = $validateColumn;
        } elseif (is_string($validateColumn)) {
            $this->validateColumn = [$validateColumn];
        } else {
            throw new InvalidArgumentException("Invalid option 'validateColumn'");
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_array($value)) {
            return false;
        }

        $validateColumn = $this->getValidateColumn();

        return key_exists($validateColumn, $value);
    }
}
