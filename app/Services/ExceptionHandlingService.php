<?php

namespace App\Services;

use App\Exceptions\HandledException;
use ArgumentCountError;
use DivisionByZeroError;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\View\ViewException;
use InvalidArgumentException;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Mailer\Exception\TransportException;
use Throwable;
use UnhandledMatchError;

class ExceptionHandlingService
{
    /**
     * @param Throwable|HttpExceptionInterface $exception
     * @return bool
     */
    public function shouldHandleException(Throwable|HttpExceptionInterface $exception): bool
    {
        return $this->mustHandleException($exception) ||
            $exception instanceof DivisionByZeroError ||
            $exception instanceof UnhandledMatchError ||
            $exception instanceof ArgumentCountError ||
            ($exception instanceof SymfonyHttpException && $exception->getStatusCode() == Response::HTTP_FORBIDDEN);
    }

    /**
     * @param Throwable|HttpExceptionInterface $exception
     * @return bool
     */
    public function mustHandleException(Throwable|HttpExceptionInterface $exception): bool
    {
        return
            $exception instanceof ModelNotFoundException ||
            $exception instanceof ValidationException ||
            $exception instanceof MethodNotAllowedHttpException ||
            $exception instanceof ViewException ||
            $exception instanceof AuthenticationException ||
            $exception instanceof TokenMismatchException ||
            $exception instanceof UnauthorizedException ||
            $exception instanceof CannotExecuteSolutionForNonLocalIp ||
            $exception instanceof AccessDeniedHttpException ||
            $exception instanceof AuthorizationException ||
            $exception instanceof HandledException ||
            $exception instanceof TransportException ||
            $exception instanceof InvalidArgumentException;
    }
}
