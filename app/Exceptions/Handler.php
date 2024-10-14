<?php

namespace App\Exceptions;

use Ars\Responder\Facades\Responder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Define custom log levels.
     */
    protected $levels = [];

    /**
     * Exception types that are not reported.
     */
    protected $dontReport = [];

    /**
     * Inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return Responder::unAuthenticated();
            }
        });

        $this->renderable(function (TooManyRequestsHttpException $e, $request) {
            if ($request->expectsJson()) {
                return Responder::tooManyRequests();
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return Responder::notFound();
            }
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($request->expectsJson()) {
                if ($e->getStatusCode() == 403) {
                    return Responder::unAuthorized();
                } elseif ($e->getStatusCode() == 401) {
                    return Responder::unAuthenticated();
                }
            }
        });
    }
}
