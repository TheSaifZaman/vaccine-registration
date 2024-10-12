<?php

namespace App\Enums;

use App\Traits\CommonEnumMethodsTrait;
use Exception;

enum LogTypeEnum: int
{
    use CommonEnumMethodsTrait;

    case None = 0;
    case Emergency = 1;
    case Alert = 2;
    case Critical = 3;
    case Error = 4;
    case Warning = 5;
    case Notice = 6;
    case Info = 7;
    case Debug = 8;
    case Verbose = 9;
    case Success = 10;

    /**
     * @param $param
     * @return string
     * @throws Exception
     */
    public static function getName($param)
    {
        return match ($param) {
            $param => self::toArray()[$param] ?? 'Info',
        };
    }
}
