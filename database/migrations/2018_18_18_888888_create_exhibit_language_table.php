<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;	

class CreateExhibitLanguageTable extends Migration
{
	private $tableName = "exhibit_language";
	private $tableComment = "展品信息表语种信息的语种信息";
	private $primaryId= "exhibit_language_id";

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
		$table->increments($this->primaryId);

		$table->integer("exhibit_id")->comment("主表关联")->default(0);

		$table->integer("language")->comment("语种信息表")->default(1);
		$table->string("title")->comment("标题")->nullable()->default("");
		$table->text("content")->comment("详细内容")->nullable();
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