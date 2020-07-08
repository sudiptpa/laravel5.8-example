<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

/**
 * Esewa Routes
 * ---------------------------------------------------------
 */
Route::get('/checkout/payment/esewa', [
    'name' => 'eSewa Checkout Payment',
    'as' => 'checkout.payment.esewa',
    'uses' => 'EsewaController@checkout',
]);

Route::post('/checkout/payment/{order}/esewa/process', [
    'name' => 'eSewa Checkout Payment',
    'as' => 'checkout.payment.esewa.process',
    'uses' => 'EsewaController@payment',
]);

Route::get('/checkout/payment/{order}/esewa/completed', [
    'name' => 'eSewa Payment Completed',
    'as' => 'checkout.payment.esewa.completed',
    'uses' => 'EsewaController@completed',
]);

Route::get('/checkout/payment/{order}/failed', [
    'name' => 'eSewa Payment Failed',
    'as' => 'checkout.payment.esewa.failed',
    'uses' => 'EsewaController@failed',
]);

/**
 * Zippay Routes
 * ---------------------------------------------------------
 */
Route::get('/checkout/payment/zippay', [
    'name' => 'Zip Pay Checkout Payment',
    'as' => 'checkout.payment.zippay',
    'uses' => 'ZipPayController@checkout',
]);

Route::post('/checkout/payment/{order}/zippay/process', [
    'name' => 'Zip Pay Checkout Payment',
    'as' => 'checkout.payment.zippay.process',
    'uses' => 'ZipPayController@payment',
]);

Route::get('/checkout/payment/{order}/zippay/completed', [
    'name' => 'Zip Pay Payment Completed',
    'as' => 'checkout.payment.zippay.completed',
    'uses' => 'ZipPayController@completed',
]);

Route::get('/checkout/payment/{order}/failed', [
    'name' => 'Zip Pay Payment Failed',
    'as' => 'checkout.payment.zippay.failed',
    'uses' => 'ZipPayController@failed',
]);
