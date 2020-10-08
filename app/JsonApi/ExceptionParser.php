<?php

namespace App\JsonApi;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Document\Error;
use Throwable;

class ExceptionParser extends \CloudCreativity\LaravelJsonApi\Exceptions\ExceptionParser
{

    protected Throwable $throwable;

    /**
     * @inheritDoc
     *
     * Adds authorization messages to response
     *
     * @param Throwable $e
     * @return ErrorInterface[]
     */
    protected function getErrors(Throwable $e): array
    {
        $this->throwable = $e;

        // We want to be able to display authorization response messages to be helpful.
        return $e instanceof AuthorizationException
            ? [$this->getAuthorizationError($e)]
            : parent::getErrors($e);
    }

    /**
     * Converts AuthorizationException to Error document containing supplied message
     *
     * @param AuthorizationException $e
     * @return ErrorInterface
     */
    protected function getAuthorizationError(AuthorizationException $e): ErrorInterface
    {
        return new Error(null, null, Response::HTTP_FORBIDDEN, null, 'Fobidden', $e->getMessage());
    }


    /**
     * @inheritDoc
     *
     * Adds exception details to response when app.debug is enabled
     *
     * @return ErrorInterface
     */
    protected function getDefaultError(): ErrorInterface
    {
        if (config('app.debug') && $this->throwable !== null) {
            return new Error(
                null,
                null,
                $status = Response::HTTP_INTERNAL_SERVER_ERROR,
                $this->throwable->getCode() > 0 ? (string) $this->throwable->getCode() : null,
                $this->getDefaultTitle($status),
                $this->throwable->getMessage(),
                null,
                [ 'exception' => $this->convertExceptionToArray($this->throwable) ]
            );
        }
        return parent::getDefaultError();
    }


    /**
     * Convert the given exception to an array.
     *
     * @param \Throwable $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        return [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}
