<?php
namespace App\Models;

/**
 * 模块模型
 *
 * @author lxp 20160707
 */
class TableModel extends BaseMdl
{
	protected $primaryKey = 'id';
	protected  $table='table_model';
	// 不可被批量赋值的属性，反之其他的字段都可被批量赋值
	protected $guarded = [
		'cate_id'
	];
}
