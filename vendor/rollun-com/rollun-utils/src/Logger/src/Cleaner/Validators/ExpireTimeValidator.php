<?php


namespace rollun\logger\Cleaner\Validators;


use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use RuntimeException;

/**
 *
 * Class ExpireTimeValidator
 * @package rollun\logger\Cleaner\Validators
 */
class ExpireTimeValidator implements CleaningValidatorInterface
{
    /**
     * @var int
     */
    private $secondUntilExpire;

    /**
     * ExpireTimeValidator constructor.
     * @param int $secondUntilExpire
     */
    public function __construct(int $secondUntilExpire)
    {
        $this->secondUntilExpire = $secondUntilExpire;
    }

    /**
     *
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $itemCreateDate = new \DateTime($value["timestamp"]);
        $secondsPast = time() - $itemCreateDate->getTimestamp();
        return $secondsPast < $this->secondUntilExpire;
    }
}