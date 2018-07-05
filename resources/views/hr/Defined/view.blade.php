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
            <li class="active">Quản lý định nghĩa</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý định nghĩa</h4>
                    </div>
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div style="margin-top: 10px">
                        <div class="col-sm-4" >
                            <input @if(isset($search['define_name'])) value="{{$search['define_name']}}" @endif placeholder="Tên định nghĩa" name="define_name_s" class="form-control" id="define_name_s">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="define_type" id="define_type">
                                {!! $optionDefinedType !!}
                            </select>
                        </div>
                        <div class="form-group pull-left">
                            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1">
                                <i class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}
                            </button>
                            <a class="btn btn-warning btn-sm" onclick="HR.editItem('{{FunctionLib::inputId(0)}}', WEB_ROOT + '/manager/defined/ajaxLoadForm')" title="Thêm mới">Thêm mới</a>
                        </div>

                    </div>
                    {{ Form::close() }}
                    @if($is_boss == 1)
                    <form id="formAdd" class="importExcelDefine" method="post" enctype="multipart/form-data" action="{{URL::route('hr.importDataToExcel')}}">
                        <input type="file" name="file_excel_define" id="file_excel_define">
                        <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1">
                            <i class="fa fa-search"></i> Import Excel
                        </button>
                    </form>
                    @endif
                    <div class="panel-body line" id="element">
                        @if(sizeof($data) > 0)
                            <table class="table table-bordered bg-head-table">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th>Tên định nghĩa</th>
                                    <th>Kiểu định nghĩa</th>
                                    <th class="text-center">Thứ tự</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $stt + $key+1 }}</td>
                                        <td>{{$item->define_name}}</td>
                                        <td>{{isset($arrDefinedType[$item->define_type]) ? $arrDefinedType[$item->define_type] : 'Chưa xác định'}}</td>
                                        <td class="text-center">
                                            {{$item->define_order}}
                                        </td>
                                        <td class="text-center">{{isset($arrStatus[$item->define_status]) ? $arrStatus[$item->define_status] : 'Chưa xác định'}}</td>
                                        <td class="text-center middle" align="center">
                                            @if($is_root || $permission_edit)
                                                <a class="editItem" onclick="HR.editItem('{{FunctionLib::inputId($item['define_id'])}}', WEB_ROOT + '/manager/defined/ajaxLoadForm')" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_remove)
                                                <a class="deleteItem" onclick="HR.deleteItem('{{FunctionLib::inputId($item['define_id'])}}', WEB_ROOT + '/manager/defined/deleteDefined')"><i class="fa fa-trash fa-2x"></i></a>
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
                            <div class="form-group">
                                <label for="define_name">Tên định nghĩa</label>
                                <input type="text" name="define_name" title="Tên định nghĩa" class="form-control input-required" id="define_name">
                            </div>
                            <div class="form-group">
                                <label for="define_order">Thứ tự hiển thị</label>
                                <input type="text" name="define_order" title="Thứ tự hiển thị" class="form-control" id="define_order">
                            </div>
                            <div class="form-group">
                                <label for="define_status">Kiểu định nghĩa</label>
                                <select class="form-control input-sm" name="define_type" id="define_type">
                                    {!! $optionDefinedType !!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="define_status">Trạng thái</label>
                                <select class="form-control input-sm" name="define_status" id="define_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/defined/edit/' + '{{FunctionLib::inputId(0)}}')">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu
                            </a>
                            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId(0)}}')">
                                <i class="fa fa-undo" aria-hidden="true"></i> Làm lại
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop