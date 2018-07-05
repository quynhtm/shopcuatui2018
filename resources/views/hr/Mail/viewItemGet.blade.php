<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
?>
@extends('admin.AdminLayouts.index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{FunctionLib::viewLanguage('home')}}</a>
            </li>
            <li class="active">Quản lý thư, tin nhắn đến</li>
        </ul>
    </div>
    <div class="page-content">
        @if(isset($error))
            <div class="alert alert-warning">
                <?= implode('<br>', $error) ?>
            </div>
        @endif
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-eye" aria-hidden="true"></i> Hộp thư đến</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrMailViewGet')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="textItalic">Người gửi: <b>{{isset($arrUser[$data['hr_mail_person_send']]) ? $arrUser[$data['hr_mail_person_send']] :  ''}}</b></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div>
                                            <?php
                                                $to = isset($data['hr_mail_person_recive_list']) ? explode(',', $data['hr_mail_person_recive_list']) :  array();
                                                $arrTo = array();
                                                foreach($to as $uid){
                                                    $user_name_to = isset($arrUser[$uid]) ? $arrUser[$uid] :  '';
                                                    if($user_name_to != ''){ $arrTo[] = $user_name_to; }
                                                }
                                                $arrTo = implode(', ', $arrTo);
                                            ?>
                                            <label class="textItalic">Tới: <span>{{$arrTo}}</span></label>
                                        </div>

                                        <?php
                                            $cc = isset($data['hr_mail_send_cc']) ? explode(',', $data['hr_mail_send_cc']) :  array();
                                            $arrCC = array();
                                            foreach($cc as $uid){
                                                $user_name_cc = isset($arrUser[$uid]) ? $arrUser[$uid] :  '';
                                                if($user_name_cc != ''){ $arrCC[] = $user_name_cc; }
                                            }
                                            $arrCC = implode(', ', $arrCC);
                                        ?>
                                        <?php if(sizeof($arrCC) > 0){ ?>
                                        <div><label class="textItalic">CC: <span>{{$arrCC}}</span></label></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Chủ đề:</label>
                                        <div class="textBold">@isset($data['hr_mail_name']){{$data['hr_mail_name']}}@endif</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nội dung:</label>
                                        <div class="textBold">@isset($data['hr_mail_content']){!! $data['hr_mail_content'] !!}@endif</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tệp đính kèm:</label>
                                        <div class="textBold">
                                            @if(isset($data['hr_mail_files']) && $data['hr_mail_files'] !='')
                                                <?php $arrfiles = ($data['hr_mail_files'] != '') ? unserialize($data['hr_mail_files']) : array(); ?>
                                                @foreach($arrfiles as $_key=>$file)
                                                    <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_MAIL.'/'.$id.'/'.$file}}">{{$file}}</a></div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="replyline">Nhấp vào đây để <span class="reply">Trả lời</span> hoặc <span class="forward">Chuyển tiếp</span></div>
    </div>
    <input id="parent_id" name="parent_id" @isset($data['hr_mail_id'])rel="{{$data['hr_mail_id']}}" value="{{FunctionLib::inputId($data['hr_mail_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
    <div id="getItemCurrent"></div>
</div>
@stop