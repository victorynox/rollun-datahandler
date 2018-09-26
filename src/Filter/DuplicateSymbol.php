<?php

namespace rollun\datanadler\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Class DuplicateSymbol
 * @package rollun\datanadler\Filter
 */
class DuplicateSymbol extends AbstractFilter
{
    /**
     * @var string
     */
    protected $duplicate;

    /**
     * @var string
     */
    protected $replace;

    /**
     * @var array of escaped regular expression special symbols
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
        '*' => '\*',
        '?' => '\?',
        '{' => '\{',
        '}' => '\}',
        ' ' => '\s',
    ];

    /**
     * @var int
     */
    protected $duplicateMoreThan = 1;

    /**
     * @var int
     */
    protected $duplicateLessThan = 100;

    /**
     * DuplicateSymbol constructor.
     *
     * Valid keys are:
     * - duplicate - symbol[s], which duplicate in string
     * - replace - symbol[s] to replace
     * - duplicateMoreThan - minimum of symbols to perform filter
     * - duplicateLessThan - maximum of symbols to perform filter
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param mixed $duplicate
     */
    public function setDuplicate(string $duplicate)
    {
        $this->duplicate = $duplicate;
    }

    /**
     * @return string
     */
    public function getDuplicate()
    {
        if (!isset($this->duplicate)) {
            throw new \InvalidArgumentException('Duplicate symbol filter: Duplicate is not set');
        }

        return $this->duplicate;
    }

    /**
     * @param mixed $replace
     */
    public function setReplace(string $replace)
    {
        $this->replace = $replace;
    }

    /**
     * @param int $duplicateMoreThan
     */
    public function setDuplicateMoreThan(int $duplicateMoreThan)
    {
        $this->duplicateMoreThan = $duplicateMoreThan;
    }

    /**
     * @param int $duplicateLessThan
     */
    public function setDuplicateLessThan(int $duplicateLessThan)
    {
        $this->duplicateLessThan = $duplicateLessThan;
    }

    /**
     * Search matches like 'aaaaaa' and replace it with 'a'
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (!(is_string($value) || is_array($value))) {
            return $value;
        }

        // Create copy of duplicate
        $duplicate = $this->getDuplicate();

        foreach ($this->reqExpEscapeCharacters as $search => $replace) {
            $duplicate = str_replace($search, $replace, $duplicate);
        }

        $replace = $this->replace ?? $this->duplicate;
        $reqExp = '/(' . $duplicate . '){' . $this->duplicateMoreThan . ',' . $this->duplicateLessThan . '}' . '/';
        $value = preg_replace($reqExp, $replace, $value);

        return $value;
    }
}
