<?php

namespace App\Dao;

use App\Models\BaseMdl;

/**
 * Class MenuDao
 *
 * @author lxp 20180123
 * @package App\Dao
 */
class MenuDao extends BaseMdl
{
	/**
	 * 菜单及权限配置
	 *
	 * 最大支持三级菜单
	 * 每个菜单项要包括：
	 * text 名称
	 * priv 权限名称
	 *        例：控制器路径为 App/Http/Controllers/User/UsersController.php，则权限名称为 user-users
	 *           如果要对应方法，则用冒号拼接，user-users:getlist
	 *           对应多个方法（目前没有实现验证），user-users:getlist|getedit|postsave
	 *        如果不对应具体控制器，名称则随意，但不能重复
	 *
	 * url 链接（可选）
	 * nodes 子菜单（可选）
	 * icon 图标（可选）
	 *
	 * @author lxp 20180123
	 * @return array
	 */
	public static function get_admin_menu()
	{
		$base_menu = [
			/*
			[
				'text' => '用户',
				'priv' => 'user',
				'icon' => 'fa fa-user',
				'order_num' => 10,
				'nodes' => [
					[
						'text' => '用户管理',
						'url' => route('admin.user.users'),
						'priv' => 'admin-user-users',
						'order_num' => 10,
					],
				]
			],*/
			[
				'text' => '模块管理',
				'priv' => 'user',
				'icon' => 'fa fa-user',
				'order_num' => 10,
				'nodes' => [
					[
						'text' => '表管理',
						'url' => route('admin.table.index'),
						'priv' => 'admin-table-table',
						'order_num' => 10,
					],
				]
			],
			[
				'text' => '设置',
				'priv' => 'setting',
				'icon' => 'fa fa-cog',
				'order_num' => 10,
				'nodes' => [
					[
						'text' => '网站设置',
						'url' => route('admin.setting.basesetting'),
						'priv' => 'admin-setting-basesetting',
						'order_num' => 10,
					],
					[
						'text' => '系统日志',
						'url' => route('admin.setting.systemlog'),
						'priv' => 'admin-setting-systemlog',
						'order_num' => 5,
					],
					[
						'text' => '管理员管理',
						'url' => route('admin.setting.adminusers'),
						'priv' => 'admin-setting-adminusers',
						'order_num' => 10,
					],
					[
						'text' => '用户组管理',
						'url' => route('admin.setting.admingroup'),
						'priv' => 'admin-setting-admingroup',
						'order_num' => 10,
					],
					[
						'text' => '登录日志',
						'url' => route('admin.setting.adminloginlog'),
						'priv' => 'admin-setting-adminloginlog',
						'order_num' => 10,
					]
				]
			],
		];

		//功能模块配置加载
		if (file_exists(base_path() . '/config/load_menu/base.php')) {

			$load_menu = include base_path() . '/config/load_menu/base.php';
			foreach ($base_menu as $k => $g) {
				if (isset($load_menu['update'][$g['priv']])) {
					$base_menu[$k]['nodes'] = array_merge($g['nodes'], $load_menu['update'][$g['priv']]);
				}
			}
			$list_menu = array_merge($base_menu, $load_menu['add']);
		} else {
			$load_menu = [];
			$list_menu = array_merge($base_menu, $load_menu);
		}
		return self::arraySort($list_menu, 'order_num');
	}

	/**
	 * 二维数组排序
	 *
	 * @author yyj 20180525
	 * @param array $arr 要排序的数组
	 * @param string $keys 键名
	 * @param string $type 排序类型
	 * @return array
	 */
	private static function arraySort($arr, $keys, $type = 'asc')
	{
		$keysvalue = [];
		$new_array = [];
		foreach ($arr as $k => $v) {
			if (isset($v['nodes']) && is_array($v['nodes']) && count($v['nodes']) > 1) {
				$arr[$k]['nodes'] = self::arraySort($v['nodes'], 'order_num');
			}
			$keysvalue[$k] = $v[$keys];
		}
		$type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
		reset($keysvalue);
		foreach ($keysvalue as $k => $v) {
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}

	/**
	 * 创建菜单文件
	 *
	 * @param $model
	 */
	public static function makeMenu($model)
	{

		$file_path = app_path('..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'load_menu' . DIRECTORY_SEPARATOR . $model->table_name . '_auto_menu.php');

		$content = '<?php
/**
 * Created by PhpStorm.
 * User: Zjy
 * Date: '.date("Y-m-d", time()).'
 * Time: '.date("H:i", time()).'
 */
return [
	\'text\' => \''.$model->model_name.'管理\',
	\'url\' => \'/\'.'.'env(\'ADMIN_ENTRANCE\',\'admin\').'.'\'/'.strtolower($model->real_model_name).'/\',
	\'priv\' => \'admin-'.strtolower($model->real_model_name).'-'.strtolower($model->real_model_name).'\',
	\'order_num\' => 10,
];
		';
		if (file_exists($file_path)) {
			unlink($file_path);
		}
		file_put_contents($file_path, $content);
	}
}
