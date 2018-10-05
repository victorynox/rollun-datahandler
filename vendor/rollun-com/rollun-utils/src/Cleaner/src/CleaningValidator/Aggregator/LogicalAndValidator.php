<?php


namespace rollun\utils\Cleaner\CleaningValidator\Aggregator;


use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use RuntimeException;

/**
 * If validators is empty - value is valid.
 * If one of validator is invalid - value has been invalidate
 * Class MultiplyValidator
 * @package rollun\utils\Cleaner\CleaningValidator
 */
class LogicalAndValidator extends AbstractLogicalValidator
{
    /**
     *
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $isValid = true;
        foreach ($this->validators as $validator) {
            $isValid = $validator->isValid($value);
            if(!$isValid) {break;}
        }
        return $isValid;
    }
}