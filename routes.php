<?php

use Illuminate\Support\Facades\Route;
use Alegiac\LaravelVatChecker\Http\Controllers\VatRatesController;

if ((bool) config('vat-checker.rates_api.enabled', false)) {
    Route::prefix((string) config('vat-checker.rates_api.prefix', 'vat-checker/v1'))
        ->group(function () {
            Route::get('rates', [VatRatesController::class, 'index']);
            Route::get('rates/{country}', [VatRatesController::class, 'show']);
        });
}


