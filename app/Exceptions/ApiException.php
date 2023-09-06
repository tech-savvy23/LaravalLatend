<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiException
{

    public function apiException ($request, $exception)
    {

        if ($this->isModel($exception)) {

            return $this->modelNotFound();
        }
        if ($this->isHttp($exception)) {

            return $this->notFound();
        }
        return parent::render($request, $exception);
    }
    protected function isModel($exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    protected function isHttp($exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    protected function modelNotFound()
    {
        return response()->json([
            'status' => 404,
            'errors' => 'Model Not Found',
        ], Response::HTTP_NOT_FOUND);
    }

    protected function notFound()
    {
        return response()->json([
            'status' => 404,
            'errors' => 'Not Found',
        ], Response::HTTP_NOT_FOUND);
    }

    public function internalError()
    {
        return 'Something went wrong. Please contact to support team';
    }

}
