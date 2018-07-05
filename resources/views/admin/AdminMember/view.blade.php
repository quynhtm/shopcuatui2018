<?php use App\Library\AdminFunction\FunctionLib; ?>
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
            <li class="active">Quản lý Member</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý Member</h4>
                    </div>
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div style="margin-top: 10px">
                        <div class="col-sm-4" >
                            <input @if(isset($search['member_name'])) value="{{$search['member_name']}}" @endif placeholder="Tên member" name="member_name_s" class="form-control" id="member_name_s">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="member_type" id="member_type">
                                {!! $optionDefinedType !!}
                            </select>
                        </div>
                        <div class="form-group pull-left">
                            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1">
                                <i class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}
                            </button>
                            <a class="btn btn-warning btn-sm" onclick="HR.editItem('{{FunctionLib::inputId(0)}}', WEB_ROOT + '/manager/member/ajaxLoadForm')" title="Thêm mới">Thêm mới</a>
                        </div>

                    </div>
                    {{ Form::close() }}

                    <div class="panel-body line" id="element">
                        @if(sizeof($data) > 0)
                            <table class="table table-bordered bg-head-table">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th>Tên Member</th>
                                    <th>Loại</th>
                                    <th class="text-center">Infor</th>
                                    <th class="text-center">Địa chỉ</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $stt + $key+1 }}</td>
                                        <td>{{$item->member_name}}</td>
                                        <td>{{isset($arrDefinedType[$item->member_type]) ? $arrDefinedType[$item->member_type] : 'Chưa xác định'}}</td>
                                        <td>
                                            Phone: {{$item->member_phone}}<br>
                                            Mail: {{$item->member_mail}}
                                        </td>
                                        <td class="text-center">{{$item->member_address}}</td>
                                        <td class="text-center middle" align="center">
                                            @if($item->member_status == 1)
                                                <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                            @else
                                                <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                            @endif
                                            @if($is_root || $permission_edit)
                                                <a class="editItem" onclick="HR.editItem('{{FunctionLib::inputId($item['member_id'])}}', WEB_ROOT + '/manager/member/ajaxLoadForm')" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_remove)
                                                <a class="deleteItem" onclick="HR.deleteItem('{{FunctionLib::inputId($item['member_id'])}}', WEB_ROOT + '/manager/member/deleteItem')"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert line">
                                {{FunctionLib::viewLanguage('no_data')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 panel-content loadForm">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-plus-square" aria-hidden="true"></i> Thêm mới</h4>
                    </div>
                    <div class="panel-body">
                        <form id="formAdd" method="post">
                            <input type="hidden" name="id" value="{{FunctionLib::inputId(0)}}" class="form-control" id="id">
                            <div class="form-group col-sm-12">
                                <label for="define_name">Tên Member</label>
                                <input type="text" name="member_name" title="Tên member" class="form-control input-required" id="member_name">
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="define_order">Địa chỉ</label>
                                <input type="text" name="member_address" class="form-control" id="member_address">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Phone</label>
                                <input type="text" name="member_phone" class="form-control" id="member_phone">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Mail</label>
                                <input type="text" name="member_mail" class="form-control" id="member_mail">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_status">Kiểu member</label>
                                <select class="form-control input-sm" name="member_type" id="member_type">
                                    {!! $optionDefinedType !!}
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_status">Trạng thái</label>
                                <select class="form-control input-sm" name="member_status" id="member_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Số tiền thanh toán</label>
                                <input type="text" name="member_pay_money" class="form-control" id="member_pay_money">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Ngày thanh toán</label>
                                <input type="text" name="member_date_pay" class="form-control" id="member_date_pay">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Ngày giới hạn live</label>
                                <input type="text" name="member_time_live" class="form-control" id="member_time_live">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="define_order">Limit Item</label>
                                <input type="text" name="member_limit_item" class="form-control" id="member_limit_item">
                            </div>
                            <div class="form-group col-sm-12">
                                <a class="btn btn-success" id="submit" onclick="HR.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/member/edit/' + '{{FunctionLib::inputId(0)}}')">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu
                                </a>
                                <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId(0)}}')">
                                    <i class="fa fa-undo" aria-hidden="true"></i> Làm lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop