<?php

namespace Modules\Registration\Enums;

use App\Traits\CommonEnumMethodsTrait;

enum RegistrationStatusEnum: string
{
    use CommonEnumMethodsTrait;

    case NOT_REGISTERED = 'Not registered';
    case NOT_SCHEDULED = 'Not scheduled';
    case SCHEDULED = 'Scheduled';
    case VACCINATED = 'Vaccinated';
}
