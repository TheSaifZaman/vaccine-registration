<?php

namespace App\Enums;

use App\Traits\CommonEnumMethodsTrait;

enum EntityTypeEnum: string
{
    use CommonEnumMethodsTrait;

    case PERMANENT = 'PERMANENT';
    case OPTIONAL = 'OPTIONAL';
}
