<?php
/*
 * Admin Routes
 */
Route::prefix('admin')->group(function() {

    Route::middleware('auth:admin')->group(function() {
        // Dashboard
        Route::get('/', 'DashboardController@index');

        // Products
        Route::resource('/products','ProductController');

        // Orders
        Route::resource('/orders','OrderController');
        Route::get('/confirm/{id}','OrderController@confirm')->name('order.confirm');
        Route::get('/pending/{id}','OrderController@pending')->name('order.pending');

        // Users
        Route::resource('/users','UsersController');

        // Logout
        Route::get('/logout','AdminUserController@logout');

        //Create Providers
        Route::get('/admin/providers','providersController@index')->name('providers');
        Route::post('/admin/providers','providersController@store');

    });

    // Admin Login
    Route::get('/login', 'AdminUserController@index');
    Route::post('/login', 'AdminUserController@store');
});

/*
 * Front Routes
 */

Route::get('/', 'Front\HomeController@index');

// User Registration
Route::get('/user/register','Front\RegistrationController@index');
Route::post('/user/register','Front\RegistrationController@store');

// User Login
Route::get('/user/login','Front\SessionsController@index');
Route::post('/user/login','Front\SessionsController@store');

// Logout
Route::get('/user/logout','Front\SessionsController@logout');
Route::get('/user/profile', 'Front\UserProfileController@index');
Route::get('/user/order/{id}','Front\UserProfileController@show');

//Route for remaining frontend
Route::get('/download','RouteController@download');
Route::get('/licensee-login','RouteController@licensee');
Route::get('/bin','RouteController@bin');
Route::get('/ein','RouteController@ein');
Route::get('/infringement','RouteController@infringement');
Route::get('/License-Status','RouteController@status');

// Cart
Route::get('/cart', 'Front\CartController@index');
Route::post('/cart','Front\CartController@store')->name('cart');
Route::patch('/cart/update/{product}','Front\CartController@update')->name('cart.update');
Route::delete('/cart/remove/{product}','Front\CartController@destroy')->name('cart.destroy');
Route::post('/cart/saveLater/{product}', 'Front\CartController@saveLater')->name('cart.saveLater');


// Save for later
Route::delete('/saveLater/destroy/{product}','Front\SaveLaterController@destroy')->name('saveLater.destroy');
Route::post('/cart/moveToCart/{product}','Front\SaveLaterController@moveToCart')->name('moveToCart');

// Checkout
Route::get('/checkout','Front\CheckoutController@index');
Route::post('/checkout','Front\CheckoutController@store')->name('checkout');

Route::get('empty', function() {
    Cart::instance('default')->destroy();
});
