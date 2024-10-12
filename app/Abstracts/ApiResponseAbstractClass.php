<?php

namespace App\Abstracts;

use App\Factories\FormatFactory;
use App\Contracts\ApiResponseInterface;
use App\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiResponseAbstractClass implements ApiResponseInterface
{
    /**
     * Error Response
     *
     * @param null $key
     * @param int $responseCode
     * @param null $exception
     * @param null $message
     * @param null|string $logLabel
     * @return JsonResponse
     */
    public static function returnError(
        $key = null,
        int $responseCode = Response::HTTP_BAD_REQUEST,
        $exception = null,
        $logLabel = null,
        $message = null
    ): JsonResponse
    {
        $processedResponse = ApiResponseService::returnErrorService($key, $responseCode, $exception, $logLabel, $message);
        return FormatFactory::factory()
            ->setFormat('json')
            ->getFormatter()
            ->setResult($processedResponse['result'])
            ->setResponseCode($processedResponse['response_code'])
            ->format();
    }
}
