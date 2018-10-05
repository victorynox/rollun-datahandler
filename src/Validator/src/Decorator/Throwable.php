<?php

namespace rollun\datahandler\Validator\Decorator;

use Zend\Validator\Exception\RuntimeException;
use Zend\Validator\ValidatorInterface;

/**
 * Class ExceptionInterfaceDecorator
 * @package rollun\datahandler\Validator
 */
class Throwable implements ValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var string
     */
    protected $exceptionMessage;

    /**
     * ThrowableDecorator constructor.
     * @param ValidatorInterface $validator
     * @param string $exceptionMassage
     */
    public function __construct(ValidatorInterface $validator, string $exceptionMassage = '')
    {
        $this->validator = $validator;
        $this->exceptionMessage = $exceptionMassage;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * Throw exception if $this->validator return false
     *
     * @param mixed $value
     * @return bool
     * @throws RuntimeException
     */
    public function isValid($value)
    {
        if ($this->validator->isValid($value)) {
            return true;
        }

        $messages = $this->getMessages();

        if (count($messages) > 1) {
            $message = '[' . implode(', ', $messages) . ']';
        } else {
            $message = array_shift($messages);
        }

        if ($this->exceptionMessage) {
            $message = $this->exceptionMessage . ': ' . $message;
        }

        throw new RuntimeException($message);
    }

    public function getMessages()
    {
        return $this->validator->getMessages();
    }
}
