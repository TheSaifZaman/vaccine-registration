<?php

namespace Modules\VaccineCenter\Http\Controllers;

use App\Helpers\ApiJsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\VaccineCenter\Http\Resources\VaccineCenterDropdownResource;
use Modules\VaccineCenter\Models\VaccineCenter;
use Modules\VaccineCenter\Services\VaccineCenterService;

class VaccineCenterController extends Controller
{
    /**
     * @param Request $request
     * @param VaccineCenter $vaccineCenter
     * @param VaccineCenterService $vaccineCenterService
     * @param ApiJsonResponseHelper $apiJsonResponseHelper
     * @return JsonResponse
     */
    public function indexOfDropdown(
        Request $request,
        VaccineCenter $vaccineCenter,
        VaccineCenterService $vaccineCenterService,
        ApiJsonResponseHelper $apiJsonResponseHelper
    ): JsonResponse
    {
        $data = $vaccineCenterService->processVaccineCenterForDropdown($request->input(), $vaccineCenter);
        return $data['vaccine_center']->indexData(
            $data['request'],
            $apiJsonResponseHelper,
            VaccineCenterDropdownResource::class
        );
    }
}
