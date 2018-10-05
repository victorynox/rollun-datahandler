<?php


namespace rollun\utils\Cleaner\CleaningValidator\Aggregator;


use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use RuntimeException;

/**
 * Class AbstractLogicalValidator
 * @package rollun\utils\Cleaner\CleaningValidator
 */
abstract class AbstractLogicalValidator implements CleaningValidatorInterface
{

    /** @var CleaningValidatorInterface[] */
    protected $validators;

    /**
     * MultiplyValidator constructor.
     * @param LogicalAndValidator[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
        foreach ($this->validators as $validator) {
            if($validator instanceof CleaningValidatorInterface) {
               throw new \InvalidArgumentException("validator must be implements " . CleaningValidatorInterface::class);
            }
        }
    }
}