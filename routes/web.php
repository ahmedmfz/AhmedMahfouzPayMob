<?php 

use Illuminate\Support\Facades\Route;


Route::get('paymob-check-install' ,function(){
    return 'paymob package install successfully';
});

Route::view('paymob-check-view','vendor.test_paymob_view');