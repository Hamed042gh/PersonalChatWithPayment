<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\PaymentThrottle;
use App\Http\Middleware\DailyChatThrottle;
use App\Http\Middleware\UserMessagesLimit;
use App\Http\Middleware\UsersPaymentLimit;
use App\Http\Middleware\UpdateLastActivity;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'update.last.activity' => UpdateLastActivity::class,
            'check.user.limit' => UserMessagesLimit::class,
            'check.payment.limit' => UsersPaymentLimit::class,
            'daily.chat.throttle' => DailyChatThrottle::class,
            'daily.payment.throttle' => PaymentThrottle::class

        ]);
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'http://localhost/payment/verify',

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
