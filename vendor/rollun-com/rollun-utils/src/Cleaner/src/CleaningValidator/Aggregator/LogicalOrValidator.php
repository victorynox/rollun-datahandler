<?php


namespace rollun\utils\Cleaner\CleaningValidator\Aggregator;


use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use RuntimeException;

/**
 * If validators is empty - value is valid.
 * If all validator is invalid - value has been invalidate.
 * Class MultiplyValidator
 * @package rollun\utils\Cleaner\CleaningValidator
 */
class LogicalOrValidator extends AbstractLogicalValidator
{
    /**
     *
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        if (empty($this->validators)) {
            return true;
        }
        $isValid = false;
        foreach ($this->validators as $validator) {
            $isValid |= $validator->isValid($value);
        }
        return $isValid;
    }
}