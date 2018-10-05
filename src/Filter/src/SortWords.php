<?php

namespace rollun\datahandler\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Class SortWords
 * @package rollun\datahandler\Filter
 */
class SortWords extends AbstractFilter
{
    /**
     * @param mixed $value
     * @return mixed|string
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $parts = explode(' ', $value);
        sort($parts);
        return implode(' ', $parts);
    }
}
