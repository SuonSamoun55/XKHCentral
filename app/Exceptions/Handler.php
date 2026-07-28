<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
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
     */
    public function register(): void
    {
        // Catch expired CSRF token / session (419 Page Expired)
        // and send the user back to login with a friendly message
        // instead of showing Laravel's raw error page.
        $this->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->with('error', 'Your session expired. Please log in again.');
        });
    }
}