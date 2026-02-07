<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ApiController;

Route::middleware(['throttle:60,1','api.credentials'])->group(function () {
	//wallets
	Route::post('create-wallet', [ApiController::class, 'createWallet']);
	Route::get('/wallets', [ApiController::class, 'wallets']);
	Route::delete('/delete-wallet/{id}', [ApiController::class, 'deleteWallet']);
});