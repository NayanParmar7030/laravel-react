<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

/*
| Same login as /api/v1/login — use this if you get 404 on /api/v1/login (wrong URL in client).
| Must be POST with JSON: { "email", "password" }.
*/
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json([
                'status' => true,
                'message' => 'Profile',
                'data' => [
                    'user' => $request->user(),
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                ],
            ]);
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        $view = PermissionMiddleware::using('view leads', 'sanctum');
        $create = PermissionMiddleware::using('create leads', 'sanctum');
        $edit = PermissionMiddleware::using('edit leads', 'sanctum');
        $delete = PermissionMiddleware::using('delete leads', 'sanctum');

        Route::middleware([$view])->get('/leads', [LeadController::class, 'index']);
        Route::middleware([$create])->post('/leads', [LeadController::class, 'store']);
        Route::middleware([$view])->get('/leads/{lead}', [LeadController::class, 'show']);
        Route::middleware([$edit])->put('/leads/{lead}', [LeadController::class, 'update']);
        Route::middleware([$edit])->patch('/leads/{lead}', [LeadController::class, 'update']);
        Route::middleware([$delete])->delete('/leads/{lead}', [LeadController::class, 'destroy']);
    });
});
