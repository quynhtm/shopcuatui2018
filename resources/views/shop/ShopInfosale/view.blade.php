@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{viewLanguage('Trang chủ')}}</a>
            </li>
            <li class="active">
                <a href="{{URL::route('shop.infosale')}}">{{$pageAdminTitle}}</a>
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-lg-3">
                                <label><i>{{viewLanguage('Tên người bán')}}</i></label>
                                <input type="text" class="form-control input-sm" name="infor_sale_name" placeholder="{{viewLanguage('Tên kiểu chuyên mục')}}" @if(isset($search['infor_sale_name']))value="{{$search['infor_sale_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label><i>{{viewLanguage('SĐT')}}</i></label>
                                <input type="text" class="form-control input-sm" name="infor_sale_phone" placeholder="{{viewLanguage('SĐT')}}" @if(isset($search['infor_sale_phone']))value="{{$search['infor_sale_phone']}}"@endif>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if($is_root || $permission_full || $permission_create)
                                <a class="btn btn-danger btn-sm" href="{{URL::route('shop.infosaleGet', ['id'=>0])}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    {{viewLanguage('Thêm mới')}}
                                </a>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> {{viewLanguage('Tìm kiếm')}}</button>
                        </div>
                    </form>
                </div>
                <br/>
                <div class="span clearfix">  Có tổng số <b>{{$total}}</b> item</div>
                <br/>
                <div class="line" id="element">
                    <table class="table table-bordered bg-head-table">
                        <thead>
                        <tr>
                            <th class="text-center w10" >{{viewLanguage('STT')}}</th>
                            <th >{{viewLanguage('Tên người bán')}}</th>
                            <th >{{viewLanguage('SĐT')}}</th>
                            <th >{{viewLanguage('Ngày tạo')}}</th>
                            <th >{{viewLanguage('Ngày cập nhật')}}</th>
                            <th class="text-center" width="7%" >{{viewLanguage('Hoạt động')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($data) && sizeof($data))
                            @foreach($data as $k=>$item)
                                <tr>
                                    <td class="text-center">{{$stt + $k + 1}}</td>  {{--$stt + $k + 1--}}
                                    <td>{{$item->infor_sale_name}}</td>
                                    <td>{{$item->infor_sale_phone}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->updated_at}}</td>
                                    <td class="text-center middle" align="center">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a href="{{URL::route('shop.infosaleGet',array('id' => $item->infor_sale_id))}}" title="{{viewLanguage('Sửa')}}">
                                                <i class="fa fa-edit fa-2x"></i>
                                            </a>
                                        @endif

                                        @if($is_root)
                                            <a href="javascript:void(0);" onclick="BE.deleteItem('{{$item->infor_sale_id}}', WEB_ROOT + '/manager/infosale/delete')" title="{{viewLanguage('Xóa')}}">
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
    </div>
</div>
@stop