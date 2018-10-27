<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class ControllerDao
{
	/**
	 * controller生成文件的位置
	 * @param $model
	 * @return string
	 */
	private static function getFileName($model){
		$table_name = $model->table_name;
		$path = app_path('Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.ucfirst($table_name));
		if(!file_exists($path)){
			mkdir($path,0,777);
		}
		$path = $path.DIRECTORY_SEPARATOR.ucfirst($table_name).'Controller.php';
		return $path;
	}

	/**
	 * 生成与语种无关的controller的内容
	 * @param $model
	 */
	private static function getControllerContentWithoutLanguage($model, &$content){
		//写明命名空间
		$content .= '<?php

namespace App\Http\Controllers\Admin\\';
		$content .= ucfirst($model->table_name).';

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\\'.ucfirst($model->table_name).';';
		$content .= '


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
	 * ';
		$content .= $model->model_name.'列表
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$list = '.ucfirst($model->table_name).'::paginate(parent::PERPAGE);
		return view(\'admin.'.$model->table_name.'.'.$model->table_name.'_list\', [\'list\' => $list]);
	}

	/**
	 * 展示'.$model->model_name.'内容
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit()
	{
		$id = request(\'id\');
		$info = '.ucfirst($model->table_name).'::find($id);';
		//判断如果是多图的话，就进行解码
		$table_struct = json_decode($model->table_struct, true);
		foreach ($table_struct as $item){
			if($item['front_type'] == 'mutiple_image'){
				$content .= '
		if($info){
			$info->'.$item['field_name'].' = \json_decode($info->'.$item['field_name'].', true);	
		}	';
			}
		}
		$content .= '				
		return view(\'admin.'.$model->table_name.'.'.$model->table_name.'_form\', [\'info\' => $info]);
	}

	/**
	 * 保存内容
	 */
	public function save(){
		$id = request(\'exhibit_id\');';
		//增加判断validate
		$content .= '
		$this->validate(request(), [';
		foreach ($table_struct as $item){
			if($item['front_type'] && empty($item['can_null'])){
				$content .= '
						\''.$item['field_name'].'\'=>'.'\'required\',';
			}
		}
		//闭合validate
		$content .= ']);';
		$content .= '
		$model = '.ucfirst($model->table_name).'::findorNew($id);';
		foreach ($table_struct as $item){
			if($item['front_type'] != 'mutiple_image'){
				$content .= '
		$model->'.$item['field_name'].' = request(\''.$item['field_name'].'\');';
			}else{
				$content .= '
		$model->'.$item['field_name'].' = json_encode(request(\''.$item['field_name'].'\'));';
			}
		}
		$content .= '		
		$model->save();
		return $this->success(route(\'admin.'.$model->table_name.'.index\'));
	}

	/**
	 * 删除内容
	 */
	public function delete(){
		$id = request(\'id\');
		'.ucfirst($model->table_name).'::where(\''.$model->primary_id.'\', $id)->delete();
		return $this->success(route(\'admin.'.$model->table_name.'.index\'));
	}
}';
		return $content;
	}

	/**
	 * 生成与语种相关的controller内容
	 * @param $model
	 * @param $content
	 */
	private static function getControllerContentWithLanguage($model, &$content){
		//写明命名空间
		$content .= '<?php

namespace App\Http\Controllers\Admin\\';
		$content .= ucfirst($model->table_name).';

use App\Http\Controllers\Admin\BaseAdminController;
// 引用主表
use App\Models\\'.ucfirst($model->table_name).';
// 引用语种信息表
use App\Models\\'.ucfirst($model->table_name).'Language;
';

		$content .= '


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
	 * ';
		$content .= $model->model_name.'列表
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$list = '.ucfirst($model->table_name).'::paginate(parent::PERPAGE);
		return view(\'admin.'.$model->table_name.'.'.$model->table_name.'_list\', [\'list\' => $list]);
	}

	/**
	 * 展示'.$model->model_name.'内容
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit()
	{
		$id = request(\'id\');
		$info = '.ucfirst($model->table_name).'::find($id);';
		//判断如果是多图的话，就进行解码
		$table_struct = json_decode($model->table_struct, true);
		foreach ($table_struct as $item){
			if($item['front_type'] == 'mutiple_image'){
				$content .= '
		if($info){
			$info->'.$item['field_name'].' = \json_decode($info->'.$item['field_name'].', true);	
		}	';
			}
		}
		//继续添加语种信息
		
		$content .= '				
		return view(\'admin.'.$model->table_name.'.'.$model->table_name.'_form\', [\'info\' => $info]);
	}

	/**
	 * 保存内容
	 */
	public function save(){
		$id = request(\'exhibit_id\');';
		//增加判断validate
		$content .= '
		$this->validate(request(), [';
		foreach ($table_struct as $item){
			if($item['front_type'] && empty($item['can_null'])){
				$content .= '
						\''.$item['field_name'].'\'=>'.'\'required\',';
			}
		}
		//闭合validate
		$content .= ']);';
		$content .= '
		$model = '.ucfirst($model->table_name).'::findorNew($id);';
		foreach ($table_struct as $item){
			if($item['front_type'] != 'mutiple_image'){
				$content .= '
		$model->'.$item['field_name'].' = request(\''.$item['field_name'].'\');';
			}else{
				$content .= '
		$model->'.$item['field_name'].' = json_encode(request(\''.$item['field_name'].'\'));';
			}
		}
		$content .= '		
		$model->save();
		return $this->success(route(\'admin.'.$model->table_name.'.index\'));
	}

	/**
	 * 删除内容
	 */
	public function delete(){
		$id = request(\'id\');
		'.ucfirst($model->table_name).'::where(\''.$model->primary_id.'\', $id)->delete();
		return $this->success(route(\'admin.'.$model->table_name.'.index\'));
	}
}';
		return $content;
	}

	/**
	 * 创建与语种相关的controller文件
	 * @param $model
	 */
	private static function makeControllerWithLanguage($model){
		$file_name = self::getFileName($model);
		$content = '';
		self::getControllerContentWithLanguage($model, $content);
		file_put_contents($file_name, $content);
	}

	/**
	 * 创建controller文件的入口函数
	 * @param $model
	 */
	public static function makeController($model){
		$table_struct = \json_decode($model->table_struct, true);
		$is_mutiple_lan = 0;
		foreach ($table_struct as $item){
			if($item['is_mutiple_lan']){
				$is_mutiple_lan = 1;
				break;
			}
		}

		if($is_mutiple_lan){
			self::makeControllerWithLanguage($model);
		}else{
			self::makeControllerWithoutLanguage($model);
		}

	}
	/**
	 * 创建控制器文件
	 * @param $model
	 */
    public static function makeControllerWithoutLanguage($model){
		$file_name = self::getFileName($model);
		$content = '';
		self::getControllerContentWithoutLanguage($model, $content);
		file_put_contents($file_name, $content);
	}
}
