<?php

namespace rollun\utils\Cleaner;

use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;
use rollun\utils\Cleaner\CleanableList\CleanableListInterface;
use rollun\utils\Cleaner\CleanerInterface;

class Cleaner implements CleanerInterface
{

    /**
     * @var CleanableListInterface
     */
    protected $cleanableList;

    /**
     * @var CleaningValidatorInterface
     */
    protected $cleaningValidator;

    public function __construct(CleanableListInterface $cleanableList, CleaningValidatorInterface $cleaningValidator)
    {
        $this->cleanableList = $cleanableList;
        $this->cleaningValidator = $cleaningValidator;
    }

    public function cleanList($data = null)
    {
        foreach ($this->cleanableList as $item) {
            if (!$this->cleaningValidator->isValid($item)) {
                $this->cleanableList->deleteItem($item);
            }
        }
    }

}
