<?php

use App\Http\Controllers\dashboard\CategoryConroller;
use App\Http\Controllers\dashboard\client\OrderController;
use App\Http\Controllers\dashboard\ClientController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\dashboard\ProductController;
use App\Http\Controllers\dashboard\UserController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::prefix('/dashboard')->name('dashboard.')->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('welcome');

        //users route
        Route::resource(
            'users',
            UserController::class,

        ); //end of users routes



        //categories route
        Route::resource(
            'categories',
            CategoryConroller::class,

        ); //end of categories routes


        //products route
        Route::resource(
            'products',
            ProductController::class,

        ); //end of products routes


        //Clients route
        Route::resource(
            'clients',
            ClientController::class,
        ); //end of clients routes

        //Clients Order route
        Route::resource(
            'clients.orders',
            OrderController::class,

        ); //end of clients order routes

        //orders route
        Route::resource(
            'orders',
            DashboardOrderController::class


        ); //end of orders routes


        Route::get('orders/{order}/products', [DashboardOrderController::class, 'products'])->name('orders.products');
    });
});


?>

<?php
