<?php

Route::group([
        'prefix' => 'admin',
        'namespace' => 'ImageManager\\Http\\Controllers',
        'middleware' => ['web', 'auth']
    ],
    function ()
    {
        Route::post('/image-manager/', 'MainController@index')->name('admin.image-manager');
        Route::post('/image-manager/folder', 'MainController@content')->name('admin.image-manager.folder');
        Route::post('/image-manager/add-folder', 'MainController@addFolder')->name('admin.image-manager.add-folder');
    }

);
