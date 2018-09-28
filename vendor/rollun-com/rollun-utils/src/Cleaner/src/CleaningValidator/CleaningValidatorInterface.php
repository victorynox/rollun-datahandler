<?php

namespace rollun\utils\Cleaner\CleaningValidator;

use RuntimeException;

interface CleaningValidatorInterface
{

    /**
     * 
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible
     */
    public function isValid($value);
}
