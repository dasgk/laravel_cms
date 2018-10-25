<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class RouteDao
{

	private static function getFileName($model){
		$path = base_path('routes'.DIRECTORY_SEPARATOR.'web_b_'.$model->table_name.'.php');
		return $path;
	}

	/**
	 * 此番我们增加四个路由 列表，展示内容，保存，删除
	 * @param $model
	 */
    public static function makeRoute($model){
    	$file_name = self::getFileName($model);
    	$content = "";
    	$content .= '
    	<?php
Route::group([
    \'prefix\' => \''.$model->table_name.'\',
    \'namespace\' => \''.ucfirst($model->table_name).'\'
], function () {
    // 用户管理列表
    Route::get(\'/\', \''.ucfirst($model->table_name).'Controller@index\')->name(\'admin.'.$model->table_name.'.index\');
    Route::get(\'/edit\', \''.ucfirst($model->table_name).'Controller@edit\')->name(\'admin.'.$model->table_name.'.edit\');
    Route::post(\'/save\', \''.ucfirst($model->table_name).'Controller@save\')->name(\'admin.'.$model->table_name.'.save\');
    Route::get(\'/delete\', \''.ucfirst($model->table_name).'Controller@delete\')->name(\'admin.'.$model->table_name.'.delete\');

});';
    	file_put_contents($file_name,$content);
	}
}
