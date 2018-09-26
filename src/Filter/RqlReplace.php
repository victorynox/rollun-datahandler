<?php

namespace rollun\datanadler\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\FilterInterface;

/**
 * Class RqlReplace
 * @package rollun\datanadler\Filter
 */
class RqlReplace extends AbstractFilter implements FilterInterface
{
    /**
     * Mapping rql node like symbols => regular expression symbols
     *
     * @var array
     */
    protected $rqlToRegExp = [
        '*' => '.+',
        '?' => '.',
        ' ' => '\s',
    ];

    /**
     * Mapping regular expression symbols with their escaped analogs
     *
     * @var array
     */
    protected $reqExpEscapeCharacters = [
        // begin from '\' character, because it symbol need to escape other symbols
        '\\' => '\\\\',
        ')' => '\)',
        '(' => '\(',
        '/' => '\/',
        '[' => '\[',
        ']' => '\]',
        '^' => '\^',
        '$' => '\$',
        '.' => '\.',
        '|' => '\|',
        '+' => '\+',
        '{' => '\{',
        '}' => '\}',
    ];

    protected $replacement = '';

    /**
     * Mask before symbol, which will be find and processed (rql query)
     *
     * @var string
     */
    protected $beforePattern;

    /**
     * The symbol, which will be find and processed (rql query)
     *
     * @var string
     */
    protected $pattern;

    /**
     * Mask after symbol, which will be find and processed (rql query)
     *
     * @var string
     */
    protected $afterPattern;

    /**
     * StripSymbolFilter constructor.
     *
     * Valid keys are:
     * - beforePattern
     * - afterPattern
     * - symbol
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param $beforePattern
     */
    public function setBeforePattern($beforePattern)
    {
        $this->beforePattern = $beforePattern;
    }

    /**
     * @param $afterPattern
     */
    public function setAfterPattern($afterPattern)
    {
        $this->afterPattern = $afterPattern;
    }

    /**
     * @param $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param $replacement
     */
    public function setReplacement($replacement)
    {
        $this->replacement = $replacement;
    }

    /**
     * Replace pattern with one space
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $fullPattern = $this->beforePattern . $this->pattern . $this->afterPattern;
        $fullRegExp = $this->getRegExp($fullPattern);

        if (preg_match($fullRegExp, $value)) {
            $definitePattern = $this->getRegExp($this->pattern);
            $value = preg_replace($definitePattern, $this->replacement, $value);
        }

        return $value;
    }

    /**
     * Prepare regular expression
     *
     * @param $pattern
     * @return null|string|string[]
     */
    protected function getRegExp(string $pattern)
    {
        $pattern = $this->processPattern($pattern);
        $regExp = '/' . $pattern . '/';

        return $regExp;
    }

    /**
     * @param $pattern
     * @return mixed
     */
    protected function processPattern(string $pattern)
    {
        // escape special regular expression symbols
        foreach ($this->reqExpEscapeCharacters as $search => $replace) {
            $pattern = str_replace($search, $replace, $pattern);
        }

        // translate rql mask symbols to regular expression symbols
        foreach ($this->rqlToRegExp as $search => $replace) {
            $pattern = str_replace($search, $replace, $pattern);
        }

        return $pattern;
    }
}
