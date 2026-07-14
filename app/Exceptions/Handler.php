<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Psr\Log\LoggerInterface;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Handler extends ExceptionHandler
{
    public function report(Throwable $e): void
    {
        $logger = app(LoggerInterface::class);

        $context = [
            'path' => request()?->path(),
            'method' => request()?->method(),
            'ip' => request()?->ip(),
            'user_id' => Auth::id(),
            'input' => Arr::except(request()?->all() ?? [], ['password', 'password_confirmation']),
        ];

        $logger->error($e->getMessage(), array_merge($context, ['exception' => $e]));

        parent::report($e);
    }

    public function render($request, Throwable $e)
    {
        // For AJAX/JSON requests return standardized JSON error
        if ($request->wantsJson() || $request->is('api/*')) {
            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            return response()->json([
                'error' => class_basename($e),
                'message' => $e->getMessage() ?: 'Server Error',
            ], $status);
        }

        return parent::render($request, $e);
    }
}
