<?php

namespace Modules\Registration\Http\Controllers;

use App\Enums\LogLabelEnum;
use App\Enums\ResponseMessageEnum;
use App\Helpers\ApiJsonResponseHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Registration\Http\Requests\SearchRegistrationRequest;
use Modules\Registration\Services\RegistrationService;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('search.index');
    }

    /**
     * @param SearchRegistrationRequest $request
     * @param RegistrationService $registrationService
     * @return JsonResponse
     */
    public function search(SearchRegistrationRequest $request, RegistrationService $registrationService): JsonResponse
    {
        DB::beginTransaction();
        try {
            $message = $registrationService->processSearch($request->validated('nid'));
            DB::commit();
            return ApiJsonResponseHelper::returnSuccess(
                [
                    ResponseMessageEnum::SUCCESS_MESSAGE->name => $message,
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

