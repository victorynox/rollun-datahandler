<?php


namespace rollun\logger\Formatter;

use DateTime;
use rollun\utils\Json\Serializer;
use Zend\Log\Formatter\FormatterInterface;

class JsonMessage implements FormatterInterface
{

    /**
     * @var string
     */
    private $dateTimeFormat;

    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param array $event event data
     * @return string|array Either a formatted line to write to the log, or the
     *     updated event information to provide to the writer.
     * @throws \rollun\utils\Json\Exception
     */
    public function format($event)
    {
        $message = Serializer::jsonSerialize($event);
        return $message;
    }

    /**
     * Get the format specifier for DateTime objects
     *
     * @return string
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    /**
     * Set the format specifier for DateTime objects
     *
     * @see http://php.net/manual/en/function.date.php
     * @param string $dateTimeFormat DateTime format
     * @return FormatterInterface
     */
    public function setDateTimeFormat($dateTimeFormat)
    {
        $this->dateTimeFormat = $dateTimeFormat;
        return $this;
    }
}