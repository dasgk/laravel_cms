<?php

namespace App\Http\Controllers\Admin\Test;

use App\Dao\MigrationDao;
use App\Dao\ViewDao;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\UploadedFile;
use App\Models\Users;
use App\Models\UsersBind;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\TableModel;

/**
 * 表控制器
 *
 * @author lxp
 * @package App\Http\Controllers\User
 */
class TestController extends BaseAdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 数据库表列表
     *
     * @author lxp 20170111
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        return view('admin.exhibit.exhibit_form', ['info'=>[]]);
    }


}