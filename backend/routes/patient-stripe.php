<?php

use App\Http\Controllers\Patient\StripeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:PATIENT'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/stripe/checkout/success/{paiement}', [StripeController::class, 'success'])->name('stripe.checkout.success');
    Route::get('/stripe/checkout/cancel/{paiement}', [StripeController::class, 'cancel'])->name('stripe.checkout.cancel');
});

Route::post('/patient/stripe/webhook', [StripeController::class, 'webhook'])->name('patient.stripe.webhook');
