<?php

namespace App\Traits;

trait CommonEnumMethodsTrait
{

    /**
     * @param bool $flipped
     * @return array
     */
    public static function toArray(bool $flipped = false): array
    {
        $array = [];
        $key = 'name';
        $value =  'value';
        if($flipped){
            $key = 'value';
            $value =  'name';
        }
        foreach (self::cases() as $case) {
            $array[$case->{$key}] = $case->{$value};
        }
        return $array;
    }

    /**
     * @param $param
     * @return string|null
     */
    public static function getValue($param): ?string
    {
        return match ($param) {
            $param => self::toArray()[$param] ?? null,
            default => null,
        };
    }

    /**
     * @param null $parameter
     * @return string|null
     */
    public static function toString($parameter = null): ?string
    {
        return ($parameter === config('settings.keyword.key'))
            ? implode(',', array_keys(self::toArray()))
            : implode(',', self::toArray());
    }
}
