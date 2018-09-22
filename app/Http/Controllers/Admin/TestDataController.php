<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class TestDataController extends BaseAdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function testDataUser(){
        $users = DB::table('users')->get();
        $ojb = new User();
        $i=0;
        foreach ($users as $u){
            $dataUserNew = [
                'user_id'=>$u->id,
                'user_full_name'=>$u->name,
                'user_name'=>$u->account,
                'user_password'=>'12345678aA',
                'user_group'=>3,
                'user_group_menu'=>76,
                'user_email'=>$u->email,
                'user_status'=>($u->role_id == 15)? STATUS_BLOCK: STATUS_SHOW,
            ];
            $ojb->createNew($dataUserNew);
            $i++;
        }
        vmDebug($i);
    }

}
