<?php

namespace App\Exceptions;

use App\Http\Traits\HandleApi;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HandleApi;


    public function register()
    {
        $this->renderable(function (CustomPostTooLargeException $e, $request) {
            return $e->render($request);
        });
    }

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof ModelNotFoundException) {
            $model = explode('\\' , $e->getModel())[2];
            return $this->sendError('Model is not found',  $model . ' is not found');
        }

        if ($e instanceof ValidationException) {
            $error = $e->validator->errors()->first();
            return $this->sendError('ValidationException', $error);
        }

        if ($e instanceof QueryException) {
            return $this->sendError('QueryException', $e->getMessage());
        }

        if ($e instanceof PostTooLargeException) {
            return $this->sendError('PostTooLargeException','The request data is too large.');
        }

//        return parent::render($request, $e);
        return $this->sendError('Server Error', $e->getMessage());

    }
}
