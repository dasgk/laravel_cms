<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;	

class CreateExhibitTable extends Migration
{
	private $tableName = "exhibit";
	private $tableComment = "展品信息表";
	private $primaryId= "exhibit_id";

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
		$table->increments($this->primaryId);
		$table->string("title")->comment("展品中文名称")->nullable()->default("");
		$table->string("file_path")->comment("音频文件")->nullable()->default("");
		$table->string("list_img")->comment("列表图")->nullable()->default("");
		$table->string("content")->comment("详细内容")->nullable()->default("111");
		$table->timestamps();
			if (env('DB_CONNECTION') == 'oracle') {
				$table->comment = $this->tableComment;
			}
		});

		if (env('DB_CONNECTION') == 'mysql') {
			DB::statement("ALTER TABLE `" . DB::getTablePrefix() . $this->tableName . "` comment '{$this->tableComment}'");
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
		if (env('DB_CONNECTION') == 'oracle') {
			$sequence = DB::getSequence();
			$sequence->drop(strtoupper($this->tableName . '_article_id_SEQ'));
		}
	}
}