<?php

namespace rollun\datanadler\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Class RemoveAllExceptDigits
 * @package rollun\datanadler\Filter
 */
class RemoveAllExceptDigits extends AbstractFilter
{
    /**
     * Remove all symbols except digits in string
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (!(is_string($value) || is_array($value))) {
            return $value;
        }

        $value = preg_replace('/\D/', ' ', $value);

        return $value;
    }
}
