<?php

namespace App\Traits\BaseModel\Actions;

use App\Enums\LogLabelEnum;
use App\Enums\ResponseMessageEnum;
use App\Contracts\ApiResponseInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

trait StoreDataTrait
{
    /**
     * Store Data
     * @param array $inputArray
     * @param ApiResponseInterface $apiResponseHelper
     * @param null $resource
     * @return JsonResponse
     */
    public function storeData(array $inputArray, ApiResponseInterface $apiResponseHelper, $resource = null)
    {
        DB::beginTransaction();
        try {
            $model = $this->create($inputArray);
            $result = $resource ? new $resource($model) : $model;
            DB::commit();
            return $apiResponseHelper::returnSuccess($result, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return $apiResponseHelper::returnError(
                ResponseMessageEnum::BAD_REQUEST->value,
                Response::HTTP_BAD_REQUEST,
                $e,
                LogLabelEnum::STORE->value
            );
        }
    }
}
