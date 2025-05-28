<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

Route::middleware('api')->group(function () {
    Route::apiResource('users', UserController::class, [
        'parameters' => ['users' => 'user_id']
    ]);

    Route::apiResource('authors', AuthorController::class, [
        'parameters' => ['authors' => 'author_id']
    ]);

    Route::apiResource('books', BookController::class, [
        'parameters' => ['books' => 'book_id']
    ]);

    Route::apiResource('loans', LoanController::class, [
        'parameters' => ['loans' => 'loan_id']
    ]);
    
    Route::patch('loans/{loan_id}/return', [LoanController::class, 'returnBook']);
});