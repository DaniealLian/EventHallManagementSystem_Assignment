<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{        public function render($rq, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
        
            Log::warning('Unable to find model', [
                'url'    => $rq->fullUrl(),
                'method' => $rq->method(),
                'user'   => optional($rq->user())->id,
            ]);
            return response()->view('errors.404', [], 404);
        }
        return parent::render($rq, $e);
    }
}
