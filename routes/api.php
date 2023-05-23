<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::post("/login", [LoginController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/users/current", [UserController::class, 'showCurrent'])->name('users.show_current');
    Route::post("/logout", LogoutController::class)->name('logout');
    Route::get("/accounts", [AccountController::class, 'index'])->name('accounts.show_all');
    Route::post("/accounts", [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{accountNumber}/transactions', [TransactionController::class, 'index'])->name('accounts.show_transactions');
    Route::get("/accounts/{accountNumber}", [AccountController::class, 'show'])->name('accounts.show');
    Route::post("/accounts/{accountNumber}", [AccountController::class, 'update'])->name('accounts.update');
    
    Route::get('/months', function () {
        $result = [];
        for ($i = 1; $i < 13; $i++) {
            array_push($result, [
                'label' => Carbon::create(month: $i)->monthName,
                'value' => $i
            ]);
        }
        return response()->json($result);
    });
});
