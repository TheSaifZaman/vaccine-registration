<?php

namespace App\Helpers;

use App\Abstracts\ApiResponseAbstractClass;
use App\Factories\FormatFactory;
use Symfony\Component\HttpFoundation\Response;

class ModelReturnResponseHelper extends ApiResponseAbstractClass
{
    /**
     * Success Response
     *
     * @param null $result
     * @param int $responseCode
     * @param array $headers
     * @return mixed
     */
    public static function returnSuccess($result = null, int $responseCode = Response::HTTP_OK, array $headers = []): mixed
    {
        return FormatFactory::factory()
            ->setFormat('model')
            ->getFormatter()
            ->setResult($result)
            ->format();
    }
}
