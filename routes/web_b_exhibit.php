
    	<?php
Route::group([
    'prefix' => 'exhibit',
    'namespace' => 'Exhibit'
], function () {
    // 用户管理列表
    Route::get('/', 'ExhibitController@index')->name('admin.exhibit.index');
    Route::get('/edit', 'ExhibitController@edit')->name('admin.exhibit.edit');
    Route::post('/save', 'ExhibitController@save')->name('admin.exhibit.save');
    Route::get('/delete', 'ExhibitController@delete')->name('admin.exhibit.delete');

});