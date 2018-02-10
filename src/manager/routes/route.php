<?php

Route::group([
        'prefix' => 'admin',
        'namespace' => 'ImageManager\\Http\\Controllers',
        'middleware' => ['web', 'admin', 'auth']
    ],
    function ()
    {
        Route::get('/image-manager/', 'MainController@index')->name('admin.image-manager');
        Route::get('/image-manager/folder', 'MainController@content')->name('admin.image-manager.folder');
        Route::get('/image-manager/add-folder', 'MainController@addFolder')->name('admin.image-manager.add-folder');
    }

);
