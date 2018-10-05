<?php

namespace rollun\utils\Cleaner\CleanableList;

interface CleanableListInterface extends \Traversable
{

    public function deleteItem($item);
}
