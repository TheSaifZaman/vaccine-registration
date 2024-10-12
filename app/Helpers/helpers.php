<?php

use App\Exceptions\HandledException;
use App\Helpers\TimeZoneHelper;

const API = 'api';
const CURRENT_API_VERSION = 'v1/';
const DATE_FORMAT = 'Y-m-d H:i:s';

if (!function_exists('formatDateToTz')) {
    /**
     * @param $date
     * @return string|null
     */
    function formatDateToTz($date): ?string
    {
        return $date ? TimeZoneHelper::convertUtcToGivenTimeZone(date(DATE_FORMAT, (strtotime($date)))) : null;
    }
}

if (!function_exists('loadConfigData')) {

    /**
     * @param string $config
     * @return mixed
     * @throws HandledException
     */
    function loadConfigData(string $config): mixed
    {
        $data = config($config);

        if (empty($data)) {
            throw new HandledException("Error: {$config}.php not loaded. Run [php artisan optimize:clear] and try again.");
        }

        return $data;
    }
}

if (!function_exists('unsetArrayKeys')) {

    /**
     * @param array $givenArray
     * @param array|null $keys
     * @param array|null $values
     * @return array
     */
    function unsetArrayKeys(array $givenArray, array $keys = null, array $values = null): array
    {
        if ($keys) {
            $givenArray = array_diff_key($givenArray, array_flip($keys));
        }
        if ($values) {
            $givenArray = array_diff($givenArray, $values);
        }
        return $givenArray;
    }
}
