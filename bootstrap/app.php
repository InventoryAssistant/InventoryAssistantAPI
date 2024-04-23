<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /* Not Found Exception */
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        });

        /* Authentication Exception */
        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated'
            ],401);
        });

        /* Authorization Exception */
        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized'
            ],403);
        });

        /* Query Exception */
        $exceptions->renderable(function (QueryException $e) {
            // If the data already exists return custom error message
            if($e->getCode() === "23000"){
                return response(['error' => 'Data already exists.'], 400);
            }

            // else return laravel error message
            return response()->json($e);
        });
    })->create();
