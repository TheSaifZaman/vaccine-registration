<?php

namespace App\Services;

use App\Enums\LogLabelEnum;
use App\Enums\ResponseMessageEnum;
use App\Helpers\LogHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseService
{
    /**
     * @param $key
     * @param int $responseCode
     * @param $exception
     * @param $logLabel
     * @param $message
     * @return array
     */
    public static function returnErrorService(
        $key = null,
        int $responseCode = Response::HTTP_BAD_REQUEST,
        $exception = null,
        $logLabel = null,
        $message = null
    ): array
    {
        list(
            $exceptionClassFlag,
            $isExceptionString,
            $logLabel,
            $message,
            $errors,
            $responseCode
            ) = self::determineErrorMessage(
            responseCode: $responseCode,
            exception: $exception,
            logLabel: $logLabel,
            message: $message
        );
        if (self::shouldLogException(
            exceptionClassFlag: $exceptionClassFlag,
            isExceptionString: $isExceptionString,
            exception: $exception)
        ) {
            self::logException(
                exception: $exception,
                logLabel: $logLabel
            );
        }
        return self::prepareErrorResponse(
            key: $key,
            message: $message,
            errors: $errors,
            responseCode: $responseCode
        );
    }

    /**
     * @param $responseCode
     * @param $exception
     * @param $logLabel
     * @param $message
     * @return array
     */
    private static function determineErrorMessage($responseCode, $exception = null, $logLabel = null, $message = null): array
    {
        $isExceptionSet = isset($exception);
        $isExInstanceOfExClass = ($exception instanceof Exception);
        $exceptionClassFlag = $isExceptionSet && $isExInstanceOfExClass;
        $isExceptionString = $isExceptionSet && !$isExInstanceOfExClass && is_string($exception);
        $exceptionMessage = $exceptionClassFlag ? $exception->getMessage() : '';
        $isExceptionMessageString = is_string($exceptionMessage);

        $message = $message ?? match (true) {
            $isExceptionString => $exception,
            $isExceptionMessageString => $exceptionMessage,
            default => config('settings.message.bad_request'),
        };

        $errors = ResponseMessageEnum::ERROR_OCCURRED->value;
        $logLabel = $logLabel ?? LogLabelEnum::INDEX->value;

        if ($exception instanceof ValidationException) {
            $message = $exception->getMessage();
            $errors = $exception->errors();
            $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        }
        if ($exception instanceof AuthenticationException) {
            $responseCode = Response::HTTP_UNAUTHORIZED;
        }
        if ($exception instanceof AuthorizationException) {
            $responseCode = Response::HTTP_FORBIDDEN;
        }

        return [
            $exceptionClassFlag,
            $isExceptionString,
            $logLabel,
            $message,
            $errors,
            $responseCode
        ];
    }

    /**
     * @param $exceptionClassFlag
     * @param $isExceptionString
     * @param null $exception
     * @return bool
     */
    private static function shouldLogException($exceptionClassFlag, $isExceptionString, $exception = null): bool
    {
        $exceptionHandlingService = resolve(ExceptionHandlingService::class);

        return ($exceptionClassFlag
                && !($exceptionHandlingService->mustHandleException($exception))
                && !($exception instanceof ValidationException)
            ) || $isExceptionString;
    }

    /**
     * @param $exception
     * @param $logLabel
     * @return void
     */
    private static function logException($exception, $logLabel): void
    {
        LogHelper::factory()
            ->setExceptionOrMessage($exception)
            ->setLabel($logLabel)
            ->log();
    }

    /**
     * @param $key
     * @param $message
     * @param $errors
     * @param $responseCode
     * @return array
     */
    protected static function prepareErrorResponse($key, $message, $errors, $responseCode): array
    {
        $result = [
            'key' => $key,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => Carbon::now(),
        ];

        return [
            'result' => $result,
            'response_code' => $responseCode
        ];
    }
}
