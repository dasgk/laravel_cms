<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class MigrationDao
{

	/**
	 * 生成migration文件的名称
	 *
	 * @param $table_name
	 * @return string
	 */
	private static function generate_file_name($table_name, $language = 0)
	{
		$name_prefix = "2018_18_18_888888". "_";
		if (!$language) {
			$name = $name_prefix . 'create_' . $table_name . '_table.php';
		} else {
			$name = $name_prefix . 'create_' . $table_name . '_language_table.php';
		}

		return $name;
	}

	/**
	 * 获得migration文件的前几行
	 */
	private static function generate_first_lines($table_name, $language = 0)
	{
		$content = '<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;';
		if (!$language) {
			$content .= '	

class Create' . ucfirst($table_name) . 'Table extends Migration
{
	private $tableName = ';
		} else {
			$content .= '	

class Create' . ucfirst($table_name) . 'LanguageTable extends Migration
{
	private $tableName = ';
		}
		return $content;
	}

	/**
	 * 创建主信息模块
	 *
	 * @param $model
	 */
	private static function generate_migration_without_language($model)
	{
		$table_name = $model->table_name;
		$primaryId = $model->primary_id;
		$timestamp = $model->timestamps;
		$table_comment = $model->table_comment;
		$table_struct = \json_decode($model->table_struct, true);
		$file_content = self::generate_first_lines($table_name);

		//添加表名称定义
		$file_content .= '"' . $table_name . '";';
		$file_content .= PHP_EOL;

		//添加表注释
		$file_content .= "\t" . 'private $tableComment = ';
		$file_content .= '"' . $table_comment . '";' . PHP_EOL;

		//添加主键定义
		$file_content .= "\t" . 'private $primaryId= ';
		$file_content .= '"' . $primaryId . '";' . PHP_EOL . PHP_EOL;
		//添加中间格式的内容
		$file_content .= "\t" . '/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {';
		//设置主键
		$file_content .= PHP_EOL;
		$file_content .= "\t" . "\t" . '$table->increments($this->primaryId);';
		$file_content .= PHP_EOL;
		$is_mutiple_language = 0;
		foreach ($table_struct as $item) {
			//开始处理每一行,如果是多语种字段，则当前字段不处理
			if ($item['is_mutiple_lan']) {
				$is_mutiple_language = 1;
				continue;
			}
			$file_content .= "\t\t" . '$table->' . $item['field_type'] . '("' . $item['field_name'] . '")->comment("' . $item['comment'] . '")';
			//是否可以为空
			if ($item['can_null'] || $item['field_type'] == 'text' || $item['field_type'] == 'longtext') {
				$file_content .= '->nullable()';
			}
			//是否有默认值
			//是否有默认值
			if($item['field_type'] != 'text' && $item['field_type'] != 'longtext'){
				if($item['field_type'] == 'integer' || $item['field_type'] == 'tinyInteger'){
					if(empty($item['default_value'])){
						$file_content .= '->default(0)';
					}else{
						$file_content .= '->default(' . $item['default_value'] . ')';
					}
				}else{
					$file_content .= '->default("' . $item['default_value'] . '")';
				}
			}
			$file_content .= ';' . PHP_EOL;
		}
		//是否设置时间戳
		if ($timestamp) {
			$file_content .= "\t\t" . '$table->timestamps();';
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
		file_put_contents(app_path(".." . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $file_name), $file_content);
		return $is_mutiple_language;
	}

	/**
	 * 创建语种信息表
	 *
	 * @param $model
	 */
	private static function generate_migration_with_language($model)
	{
		$table_name = $model->table_name . "_language";
		$primaryId = $table_name . '_id';
		$timestamp = $model->timestamps;
		$table_comment = $model->table_comment . '语种信息';
		$table_struct = \json_decode($model->table_struct, true);
		$file_content = self::generate_first_lines($model->table_name, 1);

		//添加表名称定义
		$file_content .= '"' . $model->table_name . '_language";';
		$file_content .= PHP_EOL;

		//添加表注释
		$file_content .= "\t" . 'private $tableComment = ';
		$file_content .= '"' . $table_comment . '的语种信息";' . PHP_EOL;

		//添加主键定义
		$file_content .= "\t" . 'private $primaryId= ';
		$file_content .= '"' . $primaryId . '";' . PHP_EOL . PHP_EOL;

		//添加中间格式的内容
		$file_content .= "\t" . '/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {';
		//设置主键
		$file_content .= PHP_EOL;
		$file_content .= "\t" . "\t" . '$table->increments($this->primaryId);';
		$file_content .= PHP_EOL;
		//设置主表的关联
		$file_content .= PHP_EOL;
		$file_content .= "\t" . "\t" . '$table->integer("' . $model->table_name . '_id")->comment("主表关联")->default(0);';
		$file_content .= PHP_EOL;
		//设置语种表
		$file_content .= PHP_EOL;
		$file_content .= "\t" . "\t" . '$table->integer("language")->comment("语种信息表")->default(1);';
		$file_content .= PHP_EOL;
		foreach ($table_struct as $item) {
			//不是语种信息的则不处理
			if (!$item['is_mutiple_lan']) {
				continue;
			}
			$file_content .= "\t\t" . '$table->' . $item['field_type'] . '("' . $item['field_name'] . '")->comment("' . $item['comment'] . '")';
			//是否可以为空
			if ($item['can_null']) {
				$file_content .= '->nullable()';
			}
			//是否有默认值
			if($item['field_type'] == 'integer'){
				if(empty($item['default_value'])){
					$file_content .= '->default(0)';
				}else{
					$file_content .= '->default(' . $item['default_value'] . ')';
				}

			}else{
				if($item['field_type'] != 'text'){
					$file_content .= '->default("' . $item['default_value'] . '")';
				}
			}

			$file_content .= ';' . PHP_EOL;
		}
		//是否设置时间戳
		if ($timestamp) {
			$file_content .= "\t\t" . '$table->timestamps();';
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
		$file_name = self::generate_file_name($model->table_name, 1);
		file_put_contents(app_path(".." . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $file_name), $file_content);
	}

	/**
	 * 创建migration文件
	 */
	public static function make_migration($model)
	{
		//创建主信息表，如果有需要语种信息，则进行创建
		$is_mutiple_language = self::generate_migration_without_language($model);

		if ($is_mutiple_language == 1) {
			self::generate_migration_with_language($model);

		}
	}

}
