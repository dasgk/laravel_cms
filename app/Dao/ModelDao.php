<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class ModelDao
{

	public static function getHeaderLines(){
		$content = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ';
		return $content;
	}

	/**
	 * 创建主信息model
	 * @param $model
	 * @return bool
	 */
	private static function makeModelWithoutLanguage($model,&$content){
		$content = self::getHeaderLines();
		$content .= ucfirst($model->real_model_name).'  extends Model
{
	protected $table = \''.$model->table_name.'\'';
		$content .= ';
	
	protected $primaryKey = ';
		$content .= '\''.$model->primary_id.'\';';
		if($model->timestamps){
			$content .= '
		
	public $timestamps = true;
	
		

};
	';
		}else
		{
			$content .= '
		
	public $timestamps = false;
	
		

};
	';
		}
		$is_mutiple_language = 0;
		$table_struct = \json_decode($model->table_struct, true);
		foreach($table_struct as $item){
			if($item['is_mutiple_lan']){
				$is_mutiple_language = 1;
			}
		}
		return $is_mutiple_language;
	}

	/**
	 * 创建语种信息model
	 * @param $model
	 * @return bool
	 */
	private static function makeModelLanguage($model, &$content){

		$content = self::getHeaderLines();
		$content .= ucfirst($model->real_model_name).'Language  extends Model
{
	protected $table = \''.$model->table_name.'_language\'';
		$content .= ';
	
	protected $primaryKey = ';
		$content .= '\''.$model->table_name.'_language_id\';';
		if($model->timestamps){
			$content .= '
		
	public $timestamps = true;
	
		

};
	';
		}else
		{
			$content .= '
		
	public $timestamps = false;
	
		

};
	';
		}

		return $content;
	}


	/**
	 * 创建model的入口函数
	 * @param $model
	 */
    public static function makeModel($model){
    	$content = '';
    	$res = self::makeModelWithoutLanguage($model, $content);
    	$path = app_path('Models');
    	file_put_contents($path.DIRECTORY_SEPARATOR.ucfirst($model->real_model_name).'.php', $content);
    	$content = '';
    	if($res){
    		self::makeModelLanguage($model, $content);
			file_put_contents($path.DIRECTORY_SEPARATOR.ucfirst($model->real_model_name).'Language.php', $content);
		}
	}
}
