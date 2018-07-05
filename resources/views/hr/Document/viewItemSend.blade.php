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
                <li class="active">Quản lý văn bản gửi đi</li>
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
                            <h4><i class="fa fa-eye" aria-hidden="true"></i> Văn bản đến</h4>
                        </div>
                        <div class="btn-group btn-group-sm pull-right mgt3">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrDocumentViewSend')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="textItalic">Người gửi: <b>{{isset($arrUser[$data['hr_document_person_send']]) ? $arrUser[$data['hr_document_person_send']] :  ''}}</b></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div>
                                                <?php
                                                $to = isset($data['hr_document_person_recive_list']) ? explode(',', $data['hr_document_person_recive_list']) :  array();
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
                                            $cc = isset($data['hr_document_send_cc']) ? explode(',', $data['hr_document_send_cc']) :  array();
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên văn bản:</label>
                                            <div class="textBold">@isset($data['hr_document_name']){{$data['hr_document_name']}}@endif</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số/ký hiệu:</label>
                                            <div class="textBold">@isset($data['hr_document_code']){{$data['hr_document_code']}}@endif</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cơ quan ban hành:</label>
                                            <div class="textBold">
                                                @if(isset($arrPromulgate[$data['hr_document_promulgate']]) && $data['hr_document_promulgate'] > -1)
                                                    {{$arrPromulgate[$data['hr_document_promulgate']]}}
                                                @else
                                                    Không xác định
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Loại văn bản:</label>
                                            <div class="textBold">
                                                @if(isset($arrType[$data['hr_document_type']]) && $data['hr_document_type'] > -1)
                                                    {{$arrType[$data['hr_document_type']]}}
                                                @else
                                                    Không xác định
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lĩnh vực:</label>
                                            <div class="textBold">
                                                @if(isset($arrField[$data['hr_document_field']]) && $data['hr_document_field'] > -1)
                                                    {{$arrField[$data['hr_document_field']]}}
                                                @else
                                                    Không xác định
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người ký:</label>
                                            <div class="textBold">@isset($data['hr_document_signer']){{$data['hr_document_signer']}}@endif</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày Ban hành:</label>
                                            <div class="textBold">
                                                @isset($data['hr_document_date_issued']) {{date('d-m-Y', $data['hr_document_date_issued'])}} @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày Hiệu lực:</label>
                                            <div class="textBold">
                                                @isset($data['hr_document_effective_date']) {{date('d-m-Y', $data['hr_document_effective_date'])}} @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày hết Hiệu lực:</label>
                                            <div class="textBold">
                                                @isset($data['hr_document_date_expired']) {{date('d-m-Y', $data['hr_document_date_expired'])}} @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày đưa tin:</label>
                                            <div class="textBold">
                                                @isset($data['hr_document_delease_date']) {{date('d-m-Y', $data['hr_document_delease_date'])}} @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Mô tả:</label>
                                            <div class="textBold">@isset($data['hr_document_desc']){!! $data['hr_document_desc'] !!}@endif</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nội dung:</label>
                                            <div class="textBold">@isset($data['hr_document_content']){!! $data['hr_document_content'] !!}@endif</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tệp đính kèm:</label>
                                            <div class="textBold">
                                                @if(isset($data['hr_document_files']) && $data['hr_document_files'] !='')
                                                    <?php $arrfiles = ($data['hr_document_files'] != '') ? unserialize($data['hr_document_files']) : array(); ?>
                                                    @foreach($arrfiles as $_key=>$file)
                                                        <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_DOCUMENT.'/'.$id.'/'.$file}}">{{$file}}</a></div>
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
            <div class="replyline">Nhấp vào đây để <span class="replyDocument">Trả lời</span> hoặc <span class="forwardDocument">Chuyển tiếp</span></div>
        </div>
        <input id="parent_id" name="parent_id" @isset($data['hr_document_id'])rel="{{$data['hr_document_id']}}" value="{{FunctionLib::inputId($data['hr_document_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
        <div id="getItemCurrent"></div>
    </div>
@stop