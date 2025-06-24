<?php
use App\Http\Middleware\BearerTokenMiddleware;
use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;


Route::middleware([BearerTokenMiddleware::class, LogRequestMiddleware::class])->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'index']);
        Route::get('/{news}', [NewsController::class, 'show']);
        Route::post('/', [NewsController::class, 'store']);
        Route::delete('/{news}', [NewsController::class, 'destroy']);
    });
});