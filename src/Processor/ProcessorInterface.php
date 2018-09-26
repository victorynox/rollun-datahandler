<?php

namespace rollun\datanadler\Processor;

/**
 * Interface ProcessorInterface
 * @package rollun\datanadler\Processor
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
