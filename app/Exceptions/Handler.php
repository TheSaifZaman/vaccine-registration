<?php

namespace App\Exceptions;

use App\Helpers\ApiJsonResponseHelper;
use App\Services\ExceptionHandlingService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }


    public const FATAL_ERROR = 'Error Occurred';
    public const DUPLICATE_DATA = 'Duplicated data';

    /**
     * @param $request
     * @param Throwable|HttpExceptionInterface $exception
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable|HttpExceptionInterface $exception)
    {
        $apiResponseHelper = resolve(ApiJsonResponseHelper::class);
        $exceptionHandlingService = resolve(ExceptionHandlingService::class);

        if ($exceptionHandlingService->shouldHandleException($exception)) {
            return $apiResponseHelper->returnError("FATAL_ERROR", Response::HTTP_BAD_REQUEST, $exception);
        }

        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        }

        return parent::render($request, $exception);
    }
}
