<?php

use NENEVEL\Base\Router;
use App\Controllers\HomeController;
use Pecee\Http\Middleware\BaseCsrfVerifier;

if(APP_TYPE !== 'api') Router::csrfVerifier(new BaseCsrfVerifier());
Router::group(['namespace' => "\App\Controllers"], function(){
    Router::get('/', 'HomeController@index')->name("index");
});