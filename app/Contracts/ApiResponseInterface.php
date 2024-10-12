<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface ApiResponseInterface
{
    public static function returnError($key = null, int $responseCode = Response::HTTP_BAD_REQUEST, $exception = null, $logLabel = null, $message = null);

    public static function returnSuccess($result = null, int $responseCode = Response::HTTP_OK, array $headers = []);
}
