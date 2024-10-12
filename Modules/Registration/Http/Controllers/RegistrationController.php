<?php

namespace Modules\Registration\Http\Controllers;

use App\Enums\LogLabelEnum;
use App\Enums\ResponseMessageEnum;
use App\Helpers\ApiJsonResponseHelper;
use App\Helpers\ModelReturnResponseHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Registration\Http\Requests\StoreRegistrationRequest;
use Modules\Registration\Models\Registration;
use Modules\Registration\Services\RegistrationService;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    public function create()
    {
        return view('registration.create');
    }

    /**
     * @param StoreRegistrationRequest $request
     * @param Registration $registration
     * @param RegistrationService $registrationService
     * @param ModelReturnResponseHelper $modelReturnResponseHelper
     * @return JsonResponse
     */
    public function store(
        StoreRegistrationRequest  $request,
        Registration              $registration,
        RegistrationService       $registrationService,
        ModelReturnResponseHelper $modelReturnResponseHelper
    ): JsonResponse
    {
        DB::beginTransaction();
        try {
            $response = $registrationService->storeService(
                $request->validated(),
                $registration,
                $modelReturnResponseHelper
            );
            if (!($response instanceof Registration)) {
                return $response;
            }
            DB::commit();
            return ApiJsonResponseHelper::returnSuccess(
                [
                    ResponseMessageEnum::SUCCESS_MESSAGE->value => config('settings.message.reg_success'),
                ]
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ApiJsonResponseHelper::returnError(
                ResponseMessageEnum::BAD_REQUEST->value,
                Response::HTTP_BAD_REQUEST,
                $e,
                LogLabelEnum::LIST->value
            );
        }
    }
}
