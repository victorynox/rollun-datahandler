<?php

namespace rollun\utils\Cleaner\CleaningValidator;

use Zend\Validator\ValidatorInterface;
use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;

class ZendValidatorAdapter implements CleaningValidatorInterface
{

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(ValidatorInterface $zendValidator)
    {
        $this->validator = $zendValidator;
    }

    public function isValid($value): bool
    {
        return $this->validator->isValid($value);
    }

}
