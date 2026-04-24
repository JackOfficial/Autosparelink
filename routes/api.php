<?php

use App\Http\Controllers\Payment\InTouchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * InTouchPay Callback Route
 * URL: /api/payments/intouch/callback
 * This route is automatically exempt from CSRF protection.
 */
Route::post('/payments/intouch/callback', [InTouchController::class, 'handleCallback'])
    ->name('api.payments.intouch.callback');

Route::get('/test-callback/{orderNumber}', function ($orderNumber) {
    $request = new Request([
        'status' => 'Successfull',
        'transactionid' => 'FAKE_' . time(),
        'requesttransactionid' => $orderNumber
    ]);

    return (new InTouchController())->handleCallback($request);
});    

/**
 * Default Sanctum User Route (Optional)
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
