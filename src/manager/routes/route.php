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
        Route::post('/image-manager/delete-folder', 'MainController@deleteFolder')->name('admin.image-manager.delete-folder');
        Route::post('/image-manager/add-image', 'MainController@addImage')->name('admin.image-manager.add-image');
        Route::post('/image-manager/rename-folder', 'MainController@renameFolder')->name('admin.image-manager.rename-folder');


    }

);
