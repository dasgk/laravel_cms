<?php
/**
 * 后台相关路由
 */
Route::group([
	'prefix' => env('ADMIN_ENTRANCE', 'admin'),
	'namespace' => 'Admin',
], function () {
	Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
	Route::post('login', 'LoginController@login');
	Route::post('logout', 'LoginController@logout')->name('admin.logout');
});

// 后台需要登录验证的路由
Route::group([
	'prefix' => env('ADMIN_ENTRANCE', 'admin'),
	'namespace' => 'Admin',
	'middleware' => 'auth.admin',
], function () {

	Route::get('welcome', 'HomeController@welcome')->name('admin.welcome');

	// Ajax上传图片 - 后台通用
	Route::post('upload', 'UploadController@uploadimg')->name('admin.upload');
	// 管理员修改密码
	Route::match([
		'get',
		'post'
	], 'setting/adminusers/password', 'Setting\AdminUsersController@password')->name('admin.setting.adminusers.password');
	// 管理员修改账户信息
	Route::match([
		'get',
		'post'
	], 'setting/adminusers/edit_userinfo', 'Setting\AdminUsersController@edit_userinfo')->name('admin.setting.adminusers.edit_userinfo');
	// 更新缓存
	Route::get('setting/basesetting/clearcache', 'Setting\BaseSettingController@clearcache')->name('admin.setting.basesetting.clearcache');

});

// 后台需要登录验证和权限验证的路由
Route::group([
	'prefix' => env('ADMIN_ENTRANCE', 'admin'),
	'namespace' => 'Admin',
	'middleware' => [
		'auth.admin',
		'priv.admin'
	]
], function () {
	Route::get('/', 'HomeController@index')->name('admin.index');
	Route::get('/welcome', 'HomeController@welcome')->name('admin.welcome');

	// 用户管理
	$path=base_path().'/routes/';
	//自动加载web_b_*.php后台路由
	$dh = opendir($path);
	while ($file = readdir($dh)) {
		$fullpath = $path . "/" . $file;
		//过滤需要排除的文件，过滤文件名含有中文的文件
		if ($file != "." && $file != ".." && strstr($file, '.php') !== false&&!preg_match('/[^\x00-\x80]/',$fullpath)) {
			if (!is_dir($fullpath)&&strstr($file, 'web_b_') !== false) {
				include_once($fullpath);
			}
		}
	}
	closedir($dh);

	/*include_once 'web_b_user.php';
	// 设置
	include_once 'web_b_setting.php';
	// 文件管理
	include_once 'web_b_file.php';
	// 文章管理
	include_once 'web_b_article.php';*/

});
