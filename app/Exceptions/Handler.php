<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    // This handles the case when a user is not authenticated
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if the request expects a JSON response (API request)
        if ($request->expectsJson()) {
            // Return a custom error message for unauthenticated API requests
            return response()->json(['message' => 'Token is required to access this resource.'], 401);
        }

        // Default behavior (e.g., for web-based routes)
        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
