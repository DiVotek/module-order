<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Controllers\CategoryController;

Route::get('/payment/webhook', [CategoryController::class, 'category'])->name('payment-webhook');
