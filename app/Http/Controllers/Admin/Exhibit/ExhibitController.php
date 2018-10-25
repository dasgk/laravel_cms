<?php

namespace App\Http\Controllers\Admin\Exhibit;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Exhibit;


/**
 * 表控制器
 *
 * @author lxp
 * @package App\Http\Controllers\User
 */
class ExhibitController extends BaseAdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 展品列表
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$list = Exhibit::paginate(parent::PERPAGE);
		return view('admin.exhibit.exhibit_list', ['list' => $list]);
	}

	/**
	 * 展示展品内容
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit()
	{
		$id = request('id');
		$info = Exhibit::find($id);
		if($info){
			$info->content = \json_decode($info->content, true);	
		}					
		return view('admin.exhibit.exhibit_form', ['info' => $info]);
	}

	/**
	 * 保存内容
	 */
	public function save(){
		$id = request('exhibit_id');
		$model = Exhibit::findorNew($id);
		$model->title = request('title');
		$model->file_path = request('file_path');
		$model->list_img = request('list_img');
		$model->content = json_encode(request('content'));		
		$model->save();
		return $this->success(route('admin.exhibit.index'));
	}

	/**
	 * 删除内容
	 */
	public function delete(){
		$id = request('id');
		Exhibit::where('exhibit_id', $id)->delete();
		return $this->success(route('admin.exhibit.index'));
	}
}