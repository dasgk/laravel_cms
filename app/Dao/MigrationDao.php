<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class MigrationDao
{
    /**
     * 生成migration文件的名称
     * @param $table_name
     * @return string
     */
    private static function generate_file_name($table_name)
    {
        $name_prefix = date("Y_m_d_His", time()) . "_";
        $name = $name_prefix . 'create_'.$table_name . '_table.php';
        return $name;
    }

    /**
     * 获得migration文件的前几行
     */
    private static function generate_first_lines($table_name)
    {
        $content = '<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Create'.ucfirst($table_name).'Table extends Migration
{
	private $tableName = ';
        return $content;
    }

    /**
     * 创建migration文件
     */
    public static function make_migration($model)
    {
        $table_name = $model->table_name;
        $primaryId = $model->primary_id;
        $timestamp = $model->timestamps;
        $table_comment = $model->table_comment;
        $table_struct = \json_decode($model->table_struct, true);
        $file_content = self::generate_first_lines($table_name);

        //添加表名称定义
        $file_content .= '"'.$table_name.'";';
        $file_content .= PHP_EOL;

        //添加表注释
        $file_content .= "\t".'private $tableComment = ';
        $file_content .= '"'.$table_comment.'";'.PHP_EOL;

        //添加主键定义
        $file_content .= "\t".'private $primaryId= ';
        $file_content .= '"'.$primaryId.'";'.PHP_EOL.PHP_EOL;
        //添加中间格式的内容
        $file_content .= "\t".'/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {';
        //设置主键
        $file_content .= PHP_EOL;
        $file_content .= "\t"."\t".'$table->increments("'.$primaryId.'");';
        $file_content .= PHP_EOL;
        foreach ($table_struct as $item) {
            //开始处理每一行
            $file_content .= "\t\t".'$table->'.$item['field_type'].'("'.$item['field_name'].'")->comment("'.$item['comment'].'")';
            //是否可以为空
            if($item['can_null']){
                $file_content .= '->nullable()';
            }
            //是否有默认值
            if($item['default_value']){
                $file_content .= '->dedfault("'.$item['default_value'].'")';
            }
            $file_content .= ';'.PHP_EOL;
        }
        //是否设置时间戳
        if($timestamp){
            $file_content.="\t\t".'$table->timestamps();';
        }
        //添加后续内容
        $file_content .= '
			if (env(\'DB_CONNECTION\') == \'oracle\') {
				$table->comment = $this->tableComment;
			}
		});

		if (env(\'DB_CONNECTION\') == \'mysql\') {
			DB::statement("ALTER TABLE `" . DB::getTablePrefix() . $this->tableName . "` comment \'{$this->tableComment}\'");
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists($this->tableName);
		if (env(\'DB_CONNECTION\') == \'oracle\') {
			$sequence = DB::getSequence();
			$sequence->drop(strtoupper($this->tableName . \'_article_id_SEQ\'));
		}
	}
}';
        $file_name = self::generate_file_name($table_name);
        file_put_contents(app_path("..".DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.$file_name),$file_content);
    }

}
