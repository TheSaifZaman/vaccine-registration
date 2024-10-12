<?php

namespace App\Helpers;

use App\Abstracts\ApiResponseAbstractClass;
use App\Factories\FormatFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponseHelper extends ApiResponseAbstractClass
{
    /**
     * Success Response
     *
     * @param null $result
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     * @throws Exception
     */
    public static function returnSuccess(
        $result = null,
        int $responseCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse
    {
        return FormatFactory::factory()
            ->setFormat('json')
            ->getFormatter()
            ->setResult($result)
            ->setResponseCode($responseCode)
            ->setHeaders($headers)
            ->format();
    }
}
