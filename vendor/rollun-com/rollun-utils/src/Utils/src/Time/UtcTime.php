<?php

namespace rollun\utils\Time;

/**
 * Class UtcTime
 * @package rollun\utils\Time
 */
abstract class UtcTime
{

    const WITHOUT_MICROSECONDS = 0;
    const WITH_TENTHS = 1; //0.1
    const WITH_HUNDREDTHS = 2; //0.01

    /**
     *
     * @param $precision
     * @return int|double
     */
    public static function getUtcTimestamp($precision = self::WITHOUT_MICROSECONDS)
    {
        return round(microtime(1) - date('Z'), $precision);
    }

    /**
     * @param $format
     * @param $time
     * @param \DateTimeZone $timeZone
     * @return int
     */
    public static function utcTimestampByDate($format, $time, \DateTimeZone $timeZone)
    {
        $dataTime = \DateTime::createFromFormat($format, $time, $timeZone);
        return $dataTime->getTimestamp();
    }
}
