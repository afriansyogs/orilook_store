<?php

use App\Http\Controllers\MasterController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;

// homepage 
Route::get('/', [MasterController::class, 'homePage'])->name('home');

// productPage
Route::get('/product', [MasterController::class, 'productPage'])->name('productPage');
Route::get('/product/detailProduct/{id}', [MasterController::class, 'detailProduct'])->name('detailProduct');

// aboutPage 
Route::get('/about', [MasterController::class, 'aboutPage'])->name('aboutPage');

// contactPage 
Route::get('/contact', [MasterController::class, 'contactPage'])->name('contactPage');

// register
Route::get('/register', [MasterController::class, 'registerPage'])->name('registerPage');
Route::post('/register', [MasterController::class, 'registerProcess'])->name('registerProcess');

// login logout 
Route::get('/login', [MasterController::class, 'loginPage'])->name('loginPage');
Route::post('/login', [MasterController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [MasterController::class, 'logout'])->name('logout');

// with middleware 
Route::middleware(['auth', AuthMiddleware::class])->group(function () {
    // updateDataUser 
    Route::get('/formUpdateDataUser', [MasterController::class, 'formUpdateDataUser'])->name('formUpdateDataUser');
    Route::post('/formUpdateDataUser', [MasterController::class, 'updateDataUserProcess'])->name('updateDataUserProcess');

    // profile 
    Route::get('/profile', [MasterController::class, 'profilePage'])->name('profilePage');
    Route::post('/updateProfile', [MasterController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/updateProfilePage', [MasterController::class,'updateProfilePage'])->name('updateProfilePage');

    // cart 
    Route::get('/cart', [MasterController::class, 'cartPage'])->name('cartPage');
    Route::post('/cart', [MasterController::class, 'addToCart'])->name('addToCart');
    Route::delete('/cart/{id}', [MasterController::class, 'deleteItemCart'])->name('deleteItemCart');
    
    // formCheckOut 
    Route::get('/formCheckout', [MasterController::class, 'formCheckout'])->name('formCheckout');
    // city 
    Route::get('/get-cities/{province_id}', [MasterController::class, 'getCities']);
    // voucher 
    Route::post('/check-voucher', [MasterController::class, 'checkVoucher'])->name('check.voucher');
    Route::post('/checkout/process', [MasterController::class, 'createOrder'])->name('checkout.process');
    
    // order 
    Route::get('/order', [MasterController::class, 'orderPage'])->name('orderPage');
    Route::get('/order/detailOrder/{id}', [MasterController::class, 'detailOrder'])->name('detailOrder');
    Route::put('/updateStatus/{id}', [MasterController::class, 'updateStatusCompleted'])->name('updateStatusCompleted');
    
    // review 
    Route::get('/reviewPage/{order}', [MasterController::class, 'reviewPage'])->name('reviewPage');
    Route::post('/addReview/{order}', [MasterController::class, 'addReview'])->name('addReview');
});

Route::post('/get-midtrans-token', [MasterController::class, 'checkout'])->name('checkout');
Route::post('/update-payment', [MasterController::class, 'updatePayment'])->name('updatePaymentStatus');
// Route::post('/midtrans/notification', [MasterController::class, 'handleMidtransNotification']);
// Route::get('/midtrans/check-payment/{order_code}', [MasterController::class, 'checkPaymentStatus']);

Route::post('/midtrans/callback', [MasterController::class, 'handleMidtransCallback'])->name('midtrans.callback');

// Route::post('/midtrans/callback', [MasterController::class, 'callback']);