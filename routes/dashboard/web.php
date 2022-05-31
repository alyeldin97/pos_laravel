<?php

use App\Http\Controllers\dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{

    Route::prefix('/dashboard')->name('dashboard.')->group(function(){
    
        Route::get('/check',[DashboardController::class,'index'])->name('index');
    });
});


?>