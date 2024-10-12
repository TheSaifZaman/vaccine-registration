<?php

namespace Modules\VaccineCenter\Services;

use Modules\VaccineCenter\Models\VaccineCenter;

class VaccineCenterService
{
    /**
     * @param array $request
     * @param VaccineCenter $vaccineCenter
     * @return array
     */
    public function processVaccineCenterForDropdown(array $request, VaccineCenter $vaccineCenter): array
    {
        if(array_key_exists('status', $request)) {
            unset($request['status']);
        }
        return [
            'vaccine_center' => $vaccineCenter->where('status', 1),
            'request' => $request,
        ];
    }
}
