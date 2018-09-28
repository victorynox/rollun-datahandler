<?php


namespace rollun\logger\Cleaner\Validators;


use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use RuntimeException;

/**
 * Class LevelValidator
 * @package rollun\logger\Cleaner\Validators
 */
class LevelValidator implements CleaningValidatorInterface
{

    /**
     * @var string
     */
    protected $level;

    /**
     * LevelValidator constructor.
     * @param string $level
     */
    public function __construct(string $level)
    {
        $this->level = $level;
    }

    /**
     *
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        return $value["item"] !== $this->level;
    }
}