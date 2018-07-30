<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use p4scu41\BaseCRUDApi\Support\ExceptionSupport;
use Psr\Log\LoggerInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        // Extends the parent method to add more information about the exception
        if ($this->shouldntReport($e)) {
            return;
        }

        // Check to see if LERN is installed otherwise you will not get an exception.
        if (app()->bound("lern")) {
            app()->make("lern")->record($e); // Record the Exception to the database
            // app()->make("lern")->notify($e); // Notify the Exception
        }

        // if (config('app.debug')) {
            try {
                $logger = $this->container->make(LoggerInterface::class);
                $logger->error(ExceptionSupport::getInfo($e));
            } catch (Exception $ex) {
                throw $e;
            }
        // }

        if (method_exists($e, 'report')) {
            return $e->report();
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Verifica si la respuesta debe retornar en formato JSON
        if ($request->isJson() || $request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }

        return parent::render($request, $exception);
    }
}
