<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
            <li class="active">Quản lý Role</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý Role</h4>
                    </div>
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div style="margin-top: 10px">
                        <div class="col-sm-4" >
                            <input @if(isset($search['role_name'])) value="{{$search['role_name']}}" @endif placeholder="Tên Role" name="role_name_s" class="form-control" id="role_name_s">
                        </div>
                        <div class="form-group pull-left">
                            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1">
                                <i class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}
                            </button>
                            <a class="btn btn-warning btn-sm" onclick="HR.editItem('{{FunctionLib::inputId(0)}}', WEB_ROOT + '/manager/role/ajaxLoadForm')" title="Thêm mới">Thêm mới</a>
                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="panel-body" id="element">
                        @if(sizeof($data) > 0)
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center center">STT</th>
                                    <th width="20%">Tên role</th>
                                    <th width="35%">Member</th>
                                    <th width="15%">Trạng thái</th>
                                    <th width="10%" class="center">Order</th>
                                    <th width="15%" class="center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td class="text-center middle">{{$key+1 }}</td>
                                        <td>{{$item['role_name']}}</td>
                                        <td>@if(isset($arrMember[$item['role_project']])){{$arrMember[$item['role_project']]}}@else -- @endif</td>
                                        <td>@if(isset($arrStatus[$item['role_status']])) {{$arrStatus[$item['role_status']]}} @endif</td>
                                        <td>{{ $item['role_order'] }}
                                        </td>
                                        <td class="center">
                                            @if($is_root || $permission_edit)
                                                <a class="editItem" onclick="HR.editItem('{{FunctionLib::inputId($item['role_id'])}}', WEB_ROOT + '/manager/role/ajaxLoadForm')" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_delete)
                                                <a class="deleteItem" onclick="HR.deleteItem('{{FunctionLib::inputId($item['role_id'])}}', WEB_ROOT + '/manager/role/deleteRole')"><i class="fa fa-trash fa-2x"></i></a>
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
                        <form id="form" method="post">
                            <input type="hidden" name="id" value="{{FunctionLib::inputId(0)}}" class="form-control" id="id">
                            <div class="form-group">
                                <label for="role_name">Tên role</label>
                                <input type="text" name="role_name" title="Tên role" class="form-control input-required" id="role_name">
                            </div>
                            <div class="form-group">
                                <label for="role_order">Thứ tự hiển thị</label>
                                <input type="text" name="role_order" title="Thứ tự hiển thị" class="form-control input-required" id="role_order">
                            </div>
                            <div class="form-group">
                                <label for="role_status">Thuộc member</label>
                                <select class="form-control input-sm" name="role_project" id="role_project">
                                    {!! $optionMember !!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="role_status">Trạng thái</label>
                                <select class="form-control input-sm" name="role_status" id="role_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#form', 'form#form :input', '#submit', WEB_ROOT + '/manager/role/addRole/' + '{{FunctionLib::inputId(0)}}')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</a>
                            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId(0)}}')"><i class="fa fa-undo" aria-hidden="true"></i> Reset</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
