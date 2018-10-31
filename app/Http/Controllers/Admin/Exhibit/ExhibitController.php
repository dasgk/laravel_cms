<?php

namespace App\Http\Controllers\Admin\Exhibit;

use App\Http\Controllers\Admin\BaseAdminController;
// 引用主表
use App\Models\Exhibit;
// 引用语种信息表
use App\Models\ExhibitLanguage;



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
			$info = $info->toArray();
		}
		
		if($info){
			$info['language'] = [];
			foreach(config('language') as $k=>$v){
				$info['language'][$k] = [];
				$language_model = ExhibitLanguage::where('exhibit_id', $id)->where('language',$k)->first();
				if($language_model){
					$info['language'][$k] = $language_model->toArray();	
				}
			}
		}
		;				
		return view('admin.exhibit.exhibit_form', ['info' => $info]);
	}

	/**
	 * 保存内容
	 */
	public function save(){
		$id = request('exhibit_id');
		$this->validate(request(), []);
		$model = Exhibit::findorNew($id);
		$model->content = request('content');
		$model->video_path = request('video_path');		
		$model->save();
		//开始处理多语种
		foreach(config('language') as $k=>$v){
				//先删除原有数据
				ExhibitLanguage::where('exhibit_id',$id)->where('language', $k)->first();
				
				$language_model = ExhibitLanguage::where('exhibit_id',$id)->where('language', $k)->first();
				if(empty($language_model)){
					$language_model = new ExhibitLanguage();
				}
				$language_model->exhibit_id = $model->exhibit_id;
				$language_model->list_img=request("list_img_$k");
				$language_model->language = $k;
				$language_model->save();
		}
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