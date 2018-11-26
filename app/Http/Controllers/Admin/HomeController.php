<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;

class HomeController extends BaseAdminController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 后台首页
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('admin.home', [
			'user' => Auth::user()
		]);
	}

	public function welcome()
	{
		return view('admin.index');
	}
}
