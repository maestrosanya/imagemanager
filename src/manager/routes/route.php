<?php

Route::group([
        'prefix' => 'admin',
        'namespace' => 'ImageManager\\Http\\Controllers',
        'middleware' => ['web', 'admin', 'auth']
    ],
    function ()
    {
        Route::get('/image-manager', 'MainController@index')->name('admin.image-manager');
    }

);
