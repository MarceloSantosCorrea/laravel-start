<?php

use App\SocialNetwork\SocialNetworkService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $socialNetworkService = new SocialNetworkService(new \App\SocialNetwork\Facebook);
    $socialNetworkService->login();

//    return app(MyHelpers::class)->setText('meu texto')->text();
//    $myHelpers = MyHelpers::setText('meu texto');
//    return $myHelpers->getText();

    //return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
