<?php

namespace App\Http\Controllers;

use App\Http\Models\CarrierSetting;
use App\Http\Models\Hr\HrMail;
use App\Http\Models\MenuSystem;
use App\Http\Models\RoleMenu;
use App\Http\Models\User;
use App\Http\Models\UserCarrierSetting;
use App\Http\Models\UserSetting;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\Response;

class BaseCronjobController extends Controller{

	public function __construct(){}

    public function returnResultSuccess($dataOutPut, $message = 'Success'){
        return Response::json(
            array(
                'intIsOK'=> 1,
                'data' => $dataOutPut,
                'message' => $message,
                'code'=>  200
            )
        );
    }

    public function returnResultError($dataOutPut, $message = 'No action'){
        $dataLog['data'] = $dataOutPut;
        $dataLog['message'] = $message;
        return Response::json(
            array(
                'intIsOK'=> -1,
                'data' => $dataOutPut,
                'message' => $message,
                'code'=>  202
            )
        );
    }
}  