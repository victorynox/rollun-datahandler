<?php

namespace rollun\datahandler\Processor;

/**
 * Interface ProcessorInterface
 * @package rollun\datahandler\Processor
 */
interface ProcessorInterface
{
    /**
     * Process array and return it
     *
     * @param array $value
     * @return mixed
     */
    public function process(array $value): array;
}
