<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\BaseAdminController;
use Psy\VarDumper\Dumper;

/**
 * 系统日志控制器
 *
 * @package App\Http\Controllers\Admin\Setting
 */
class SystemlogController extends BaseAdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 目录列表
	 *
	 * @author lxp
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$logpath = storage_path('logs');
		$list = $this->get_dir_list($logpath);

		return view('admin.setting.systemlog', ['dirlist' => $list]);
	}

	protected function get_dir_list($basedir)
	{
		if (!is_dir($basedir)) {
			return false;
		}
		$dirhandler = opendir($basedir);
		$dir_list = [];
		$file_list = [];
		// 遍历目录
		while (($dir = readdir($dirhandler)) !== false) {
			if ($dir != '..' && $dir != '.') {
				$dirpath = $basedir . '/' . $dir;
				if (is_dir($dirpath)) {
					// 处理目录
					array_push($dir_list, [
						'name' => $dir,
						'path' => $dirpath,
						'type' => 'dir'
					]);
				} elseif (file_exists($dirpath) && pathinfo($dirpath)['extension'] == 'log') {
					// 处理扩展名为log的文件
					array_push($file_list, [
						'name' => $dir,
						'path' => $dirpath,
						'type' => 'file'
					]);
				}
			}
		}

		// 倒序排序目录
		array_multisort(array_column($dir_list, 'name'), SORT_DESC, $dir_list);
		// 倒序排列文件
		array_multisort(array_column($file_list, 'name'), SORT_DESC, $file_list);

		return array_merge($dir_list, $file_list);
	}

	/**
	 * ajax获取目录中内容
	 *
	 * @author lxp
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getdir()
	{
		$path = request('path');
		$list = $this->get_dir_list($path);

		return response()->json($list);
	}

	/**
	 * 查看文件
	 *
	 * @author lxp
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
	 */
	public function view()
	{
		$filepath = request('path');
		if (!file_exists($filepath)) {
			return $this->error('文件不存在');
		}

		// 起始行数
		$start_line = request('start_line', 1);
		// 终止行数
		$end_line = request('end_line', 20);

		$forward_line = request('forward_line', 20);
		$backward_line = request('backward_line', 20);
		$filecontents = [];
		//找出第几行是开始时间
		$start_time = request('start_time');
		$real_start_bytecount = 0;
		if ($start_time) {
			$fp = fopen($filepath, 'r');
			while (!feof($fp)) {
				// 在要求行数内取得数据
				$raw_input = fgets($fp);
				$tmp = trim($raw_input);
				$tmp = json_decode($tmp, true);
				if ($tmp['time'] >= $start_time) {
					break;
				} else {
					$real_start_bytecount += strlen($raw_input);
				}
			}
			fclose($fp);
		}
		// 只读方式打开文件
		$fp = fopen($filepath, 'r');
		//移动到需要的位置
		fseek($fp, $real_start_bytecount, SEEK_SET);
		$line = 0;
		while (!feof($fp)) {
			$line++;
			if ($line >= $start_line && $line <= $end_line) {
				// 在要求行数内取得数据
				$tmp = trim(fgets($fp));
				$filecontents[] = $tmp;
			} else {
				fgets($fp);
			}
		}
		fclose($fp);
		if (isset($filecontents[0]) && is_json($filecontents[0])) {
			return view('admin.setting.systemlog_view_c', [
				'filepath' => $filepath,
				'show_filepath' => str_replace(storage_path(), '', $filepath),
				'line' => $line,
				'filecontents' => $filecontents,
				'start_line' => $start_line,
				'end_line' => $end_line,
				'forward_line' => $forward_line,
				'backward_line' => $backward_line
			]);
		} else {
			return view('admin.setting.systemlog_view', [
				'filepath' => $filepath,
				'filecontents' => $filecontents,
			]);
		}
	}
}