<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeZoneHelper
{
    /**
     * Get Default Timezone
     *
     * @return mixed
     */
    public static function getDefaultTimezone(): mixed
    {
        return config("app.timezone");
    }

    /**
     * Get User App timezone.
     *
     * @return mixed
     */
    public static function userAppTimeZone(): mixed
    {
        $userTimezone = $_COOKIE['user_timezone'] ?? '';
        if ($userTimezone) {
            return $userTimezone;
        }
        return self::getDefaultTimezone();
    }

    /**
     * @param $value
     * @param string|null $timeZone
     * @param string $givenFormat
     * @param string $expectedFormat
     * @return mixed|string
     */
    public static function convertGivenTimeZoneToUtc($value, string $timeZone = null, string $givenFormat = DATE_FORMAT, string $expectedFormat = DATE_FORMAT): mixed
    {
        if (empty($value)) {
            return $value;
        }
        $timeZone = $timeZone ?? $GLOBALS['time_zone'] ?? self::userAppTimeZone();
        $date = Carbon::createFromFormat($givenFormat, $value, $timeZone);
        $date->setTimezone('UTC');
        return $date->format($expectedFormat);
    }

    /**
     * @param $value
     * @param string|null $timeZone
     * @param string $expectedFormat
     * @param string $givenFormat
     * @return mixed|string
     */
    public static function convertUtcToGivenTimeZone($value, string $timeZone = null, string $expectedFormat = DATE_FORMAT, string $givenFormat = DATE_FORMAT): mixed
    {
        if (empty($value) || $value == '0000-00-00 00:00:00' || strtotime($value) === false) {
            return $value;
        }
        $timeZone = $timeZone ?? $GLOBALS['time_zone'] ?? self::userAppTimeZone();
        $date = Carbon::createFromFormat($givenFormat, $value, 'UTC');
        $date->setTimezone($timeZone);
        return $date->format($expectedFormat);
    }
}
