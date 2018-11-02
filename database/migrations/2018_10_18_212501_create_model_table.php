<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_model', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name')->nullable()->comment('表名称');
			$table->string('model_name')->nullable()->comment('模块名称');
            $table->string('table_comment')->nullable()->comment('表注释');
            $table->string('primary_id')->nullable()->comment('表主键');
            $table->integer('timestamps')->nullable()->comment('是否支持时间戳,默认支持')->default(1);
            $table->integer('generate_migration')->nullable()->comment('是否生成migration文件')->default(1);
            $table->integer('generate_view')->nullable()->comment('是否生成view文件')->default(1);
            $table->integer('execute_migration')->nullable()->comment('是否执行migration文件')->default(1);
            $table->integer('generate_model')->nullable()->comment('是否生成model文件')->default(1);
            $table->integer('generate_route')->nullable()->comment('是否生成route文件')->default(1);
            $table->integer('is_backup_control')->nullable()->comment('是否后台控制')->default(1);
            $table->text('table_struct')->comment('模块说明');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model');
    }
}
