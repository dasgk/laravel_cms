<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:10
 */
Route::group([
	'prefix' => 'test',
	'namespace' => 'Test'
], function () {
	// 模块装载列表
	Route::get('/', 'TestController@index')->name('admin.test.index');

});