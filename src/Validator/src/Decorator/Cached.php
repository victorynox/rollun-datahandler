<?php

namespace rollun\datahandler\Validator\Decorator;

use Zend\Validator\ValidatorInterface;

/**
 * Class CachedValidator
 * @package rollun\datahandler\Validator
 */
class Cached implements ValidatorInterface
{
    /**
     * @var ValidatorInterface[]|array
     */
    static protected $cachedValidators;

    /**
     * @var string
     */
    protected $requestedName;

    /**
     * Cached constructor.
     * @param $requestedName
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator, string $requestedName)
    {
        if (!isset(self::$cachedValidators[$requestedName])) {
            self::$cachedValidators[$requestedName] = $validator;
        }

        $this->requestedName = $requestedName;
    }

    /**
     * Get cached validator
     *
     * @return ValidatorInterface
     */
    public function getCachedValidator()
    {
        return self::$cachedValidators[$this->requestedName];
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return $this->getCachedValidator()->isValid($value);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->getCachedValidator()->getMessages();
    }
}
