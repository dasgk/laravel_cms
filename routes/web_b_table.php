<?php
Route::group([
    'prefix' => 'table',
    'namespace' => 'Table'
], function () {
    // 用户管理列表
    Route::get('/', 'TableController@index')->name('admin.table.index');
    Route::get('/edit', 'TableController@edit')->name('admin.table.edit');
    Route::post('/save', 'TableController@save')->name('admin.table.save');
    Route::get('/delete', 'TableController@delete')->name('admin.table.delete');

});