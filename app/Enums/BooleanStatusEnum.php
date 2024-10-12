<?php

namespace App\Enums;

use App\Traits\CommonEnumMethodsTrait;

enum BooleanStatusEnum: string
{
    use CommonEnumMethodsTrait;

    case YES = 'Yes';
    case NO = 'No';
    case TRUE = 'True';
    case FALSE = 'False';
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case SUCCESS = 'Success';
    case ERROR = 'Error';

    /**
     * @return array
     */
    public static function yesNoArray()
    {
        return [
            BooleanStatusEnum::YES->name => BooleanStatusEnum::YES->value,
            BooleanStatusEnum::NO->name => BooleanStatusEnum::NO->value,
        ];
    }

    /**
     * @return array
     */
    public static function truFalseArray()
    {
        return [
            BooleanStatusEnum::TRUE->name => BooleanStatusEnum::TRUE->value,
            BooleanStatusEnum::FALSE->name => BooleanStatusEnum::FALSE->value,
        ];
    }

    /**
     * @return array
     */
    public static function activeInactivePlainArray(): array
    {
        return [
            BooleanStatusEnum::INACTIVE->value,
            BooleanStatusEnum::ACTIVE->value,
        ];
    }

    /**
     * @return array
     */
    public static function yesNoPlainArray(): array
    {
        return [
            BooleanStatusEnum::NO->value,
            BooleanStatusEnum::YES->value,
        ];
    }
}
