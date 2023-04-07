<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
            /*echo $e->getCode();
            exit();*/
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            //dd($exception);
            if ($exception->getStatusCode() == 404) {
                // return response()->view('errors.' . '404', [], 404);
            }
            if ($exception->getStatusCode() == 500) {
                // return response()->view('errors.' . '404', [], 404);
            }
            if ($exception->getStatusCode() == 405) {
                // return response()->view('errors.' . '404', [], 404);
            }
            if ($exception->getStatusCode() == 419) {
                // return response()->view('errors.' . '404', [], 404);
            }
        }
        return response()->view('errors.' . '404', [], 404);
        //return parent::render($request, $exception);
    }
}
