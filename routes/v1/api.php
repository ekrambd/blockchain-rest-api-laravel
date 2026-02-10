<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ApiController;

Route::middleware(['throttle:60,1','api.credentials'])->group(function () {
	//wallets
	Route::post('create-wallet', [ApiController::class, 'createWallet']);
	Route::get('/wallets', [ApiController::class, 'wallets']);
	Route::delete('/delete-wallet/{id}', [ApiController::class, 'deleteWallet']);
	//balance
	Route::prefix('balance')->group(function () {
	    Route::post('bnb', [ApiController::class, 'bnbBalance']);
	    Route::post('polygon', [ApiController::class, 'polygonBalance']);
	    Route::post('ethereum', [ApiController::class, 'ethereumBalance']);
	});

	//decrypt info
	Route::post('wallet-info-unlock', [ApiController::class, 'walletInfoLock']);

	//max range
	Route::prefix('max-range')->group(function () {
	    Route::post('bnb', [ApiController::class, 'maxRangeBNB']);
	    Route::post('pol', [ApiController::class, 'maxRangePOL']);
	    Route::post('eth', [ApiController::class, 'maxRangeETH']);
	});
});