<?php

namespace App\Exceptions;

use CloudCreativity\LaravelJsonApi\Exceptions\HandlesErrors;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Throwable;

class Handler extends ExceptionHandler
{

    use HandlesErrors;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        JsonApiException::class,
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $e)
    {
        $this->convertExceptionToArray($e);
        if ($this->isJsonApi($request, $e)) {
            try {
                return $this->renderJsonApi($request, $e);
            } catch (Throwable $e) {
                return parent::render($request, $e);
            }
        }

        return parent::render($request, $e);
    }

    protected function prepareException(Throwable $e)
    {
        if ($e instanceof JsonApiException) {
            try {
                return $this->prepareJsonApiException($e);
            } catch (Throwable $e) {
                return parent::prepareException($e);
            }
        }

        return parent::prepareException($e);
    }


}
