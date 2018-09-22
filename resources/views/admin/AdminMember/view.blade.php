<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\CGlobal; ?>

@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{viewLanguage('Trang chủ')}}</a>
            </li>
            <li class="active">{{$pageAdminTitle}}</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> {{$pageAdminTitle}}</h4>
                    </div>
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-lg-3">
                                <label><i>{{viewLanguage('Tên')}}</i></label>
                                <input type="text" class="form-control input-sm" name="member_name" placeholder="{{viewLanguage('Tên')}}" @if(isset($search['member_name']))value="{{$search['member_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="member_status" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="member_status" id="member_status" class="form-control input-sm">
                                    {!! $optionSearch !!}}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if($is_root || $permission_full || $permission_create)
                                <a class="btn btn-danger btn-sm" href="{{URL::route('admin.viewMember')}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    {{viewLanguage('Thêm mới')}}
                                </a>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> {{viewLanguage('Tìm kiếm')}}</button>
                        </div>
                    </form>
                    <div class="panel-body line" id="element">
                        <table class="table table-bordered bg-head-table">
                            <thead>
                            <tr>
                                <th class="text-center w10">{{viewLanguage('STT')}}</th>
                                <th>{{viewLanguage('Mã tình trạng')}}</th>
                                <th>{{viewLanguage('Tên tình trạng')}}</th>
                                <th class="text-center">{{viewLanguage('Mô tả')}}</th>
                                <th class="text-center">{{viewLanguage('Trạng thái')}}</th>
                                <th class="text-center">{{viewLanguage('Hoạt động')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data) && sizeof($data))
                                @foreach($data as $k=>$item)
                                <tr>
                                    <td class="text-center">{{$stt + $k + 1}}</td>
                                    <td>{{$item->define_code}}</td>
                                    <td>{{$item->member_name}}</td>
                                    <td class="text-center">{{$item->define_note}}</td>
                                    <td class="text-center">
                                    @if($item->member_status == STATUS_SHOW)
                                        @if($item['active'] == 1)
                                            <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                        @else
                                            <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                        @endif
                                    </td>
                                     @endif
                                    <td class="text-center middle" align="center">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a class="editItem" onclick="BE.editItem('{{$item->id}}', WEB_ROOT + '/manager/member/ajaxLoad')" title="{{viewLanguage('Sửa')}}">
                                                <i class="fa fa-edit fa-2x"></i>
                                            </a>
                                        @endif
                                        @if($is_root || $permission_full || $permission_delete)
                                            <a href="javascript:void(0);" onclick="BE.deleteItem('{{$item->id}}', WEB_ROOT + '/manager/member/delete')" title="{{viewLanguage('Xóa')}}">
                                                <i class="fa fa-trash fa-2x"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $paging !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4 panel-content loadForm">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4>
                            <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <form id="formAdd" method="post">
                            <input name="id_hiden" value="0" class="form-control" id="id_hiden" type="hidden">
                            <div class="form-group">
                                <label for="member_name">{{viewLanguage('Tên tình trạng')}}</label>
                                <input name="member_name" title="{{viewLanguage('Tên tình trạng')}}" class="form-control input-required" id="member_name" type="text">
                            </div>
                            <div class="form-group">
                                <label for="member_name">{{viewLanguage('Mô tả')}}</label>
                                <textarea name="define_note" id="define_note" cols="30" rows="2" class="form-control input-required" id="member_name"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="member_status">{{viewLanguage('Trạng thái')}}</label>
                                <select class="form-control input-sm" name="member_status" id="member_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            @if($is_root || $permission_full || $permission_create)
                            <a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/member/post/0')">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}
                            </a>
                            @endif
                            <a class="btn btn-default" id="cancel" onclick="BE.resetItem('#id_hiden', '0')">
                                <i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        BE.scrolleTop();
    });
</script>
@stop