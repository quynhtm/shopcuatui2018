<?php
/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 29/05/2015
 * Time: 8:24 CH
 */

namespace App\Http\Controllers\Admin;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Session;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;

class AdminDashBoardController extends BaseAdminController
{
    private $error = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        $data = array();
        return view('admin.AdminDashBoard.index', [
            'user' => $this->user,
            'menu' => $this->menuSystem,
            'data' => $data,
            'arrNotify' => [],
            'lang' => $this->languageSite,
            'is_root' => $this->is_root]);
    }
}