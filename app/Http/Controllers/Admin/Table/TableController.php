<?php

namespace App\Http\Controllers\Admin\Table;

use App\Dao\ControllerDao;
use App\Dao\MigrationDao;
use App\Dao\ModelDao;
use App\Dao\RouteDao;
use App\Dao\ViewDao;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\UploadedFile;
use App\Models\Users;
use App\Models\UsersBind;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\TableModel;

/**
 * 表控制器
 *
 * @author lxp
 * @package App\Http\Controllers\User
 */
class TableController extends BaseAdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 数据库表列表
     *
     * @author lxp 20170111
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $table_list = TableModel::paginate(parent::PERPAGE);
        $res['list'] = $table_list;
        return view('admin.tablemodel.table_model', $res);
    }

    public function edit()
    {
        $id = request('id');
        $info = TableModel::findOrnew($id);
        $table_struct = [];
        if($info->table_struct){
            $table_struct = json_decode($info->table_struct, true);
        }
        return view('admin.tablemodel.table_model_form', ['info' => $info,'table_struct'=>$table_struct]);
    }

    public function save()
    {
        $table_id = request('id');
        $model = TableModel::findorNew($table_id);
        $model->table_name = request('table_name');
		$model->model_name = request('model_name');
        $model->primary_id = request('primary_id');
        $model->table_comment = request('table_comment');
        $model->timestamps = request('timestamps');
        $model->generate_migration = request('generate_migration');
        $model->execute_migration = request('execute_migration');
        $model->generate_model = request('generate_model');
        $model->is_backup_control = request('is_backup_control');
        $table_struct = [];
        $field_name = request('field_name');
        if($field_name){
            foreach ($field_name as $k => $v) {
            	//如果数据类型是text，则不允许有默认值
				if(request('field_type')[$k] == 'text' && request('default_value')[$k]){
					return $this->error('数据类型是text时，不允许有默认值');
				}
                $item['field_name'] = $v;
                $item['field_type'] = request('field_type')[$k];
                $item['can_null'] = request('can_null')[$k];
                $item['comment'] = request('comment')[$k];
                $item['default_value'] = request('default_value')[$k];
                $item['is_mutiple_lan'] = request('is_mutiple_lan')[$k];
                $item['front_type'] = request('front_type')[$k];
                $item['front_text'] = request('front_text')[$k];
                $item['front_value'] = request('front_value')[$k];
                $table_struct[] = $item;
            }
        }
        $model->table_struct = json_encode($table_struct);
        $model->save();
        //判断是否生成migration文件
        if(request('generate_migration')){
            MigrationDao::make_migration($model);
        }
        //判断是否执行migrate
		if(request('execute_migration')){
        	$command = 'php '.base_path('artisan').' migrate';
        	exec($command);
		}
        if(request('generate_model')){
        	ModelDao::makeModel($model);
		}
		//是否后台管理
		if( request('is_backup_control')){
        	//生成view
			ViewDao::makeListView($model);
			ViewDao::makeFormView($model);
			//生成controller
			ControllerDao::makeController($model);
			//生成route
			RouteDao::makeRoute($model);
			//生成menu
		}
        return $this->success(route('admin.table.index'));
    }

}