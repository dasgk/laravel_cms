<?php
Route::group([
	'prefix' => 'file/file',
	'namespace' => 'File'
], function () {
	// 文件列表
	Route::get('/', 'FileController@index')->name('admin.file.file');
	// 下载文件
	Route::get('/download/{file_id}', 'FileController@download')->name('admin.file.file.download');
	// 删除文件
	Route::get('/delete/{file_ids?}', 'FileController@delete')->name('admin.file.file.delete');
	// 上传文件
	Route::match([
		'get',
		'post'
	], '/upload', 'FileController@upload')->name('admin.file.file.upload');
	// 分片上传文件
	Route::match([
		'get',
		'post'
	], '/multiupload', 'FileController@multiUpload')->name('admin.file.file.multiupload');
	// 检查文件分片
	Route::get('/checkmfile', 'FileController@checkMfile')->name('admin.file.file.checkmfile');

	// 上传资源
	Route::get('/upload_resource', 'FileController@upload_resource')->name('admin.file.file.upload_resource');
	// 上传资源详情页
	Route::get('/upload_resource_html/{uploaded_type}/{file_id}/{type}/{now_num}', 'FileController@upload_resource_html')->name('admin.file.file.upload_resource_html');
	//图片裁剪
	Route::get('/cropper_upload/{file_id}/{post_name}/{width}/{height}', 'FileController@cropper_upload')->name('admin.file.file.cropper_upload');
});
