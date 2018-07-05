<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
                </li>
                <li><a href="{{URL::route('hr.personnelView')}}"> Danh sách nhân sự</a></li>
                <li class="active">Thông tin thêm của nhân sự</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}

                    <div class="span clearfix">Họ và tên:<b> Nguyễn văn A</b> </div>
                    <div class="span clearfix">Số CMTND:<b> 123456789</b> </div>
                    <div class="span clearfix">Số cán bộ:<b> 123456789</b> </div>
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> nhân sự @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="3%" class="text-center">STT</th>
                            <th width="12%">Loại hợp đồng</th>
                            <th width="20%">Chế độ thanh toán(trả lương)</th>
                            <th width="10%" class="text-center">Mã hợp đồng</th>
                            <th width="10%" class="text-center">Mức lương</th>
                            <th width="10%" class="text-center">Ngày ký</th>
                            <th width="10%" class="text-center">Ngày hiệu lực</th>
                            <th width="15%" class="text-center">Thỏa thuận khác</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        @if(sizeof($contracts) > 0)
                            <tbody>
                            @foreach ($contracts as $key => $item)
                                <tr>
                                    <td class="text-center middle">{{ $key+1 }}</td>
                                    <td class="text-center middle">{{ $item['contracts_type_define_name'] }}</td>
                                    <td class="text-center middle">{{ $item['contracts_payment_define_name'] }}</td>
                                    <td class="text-center middle">{{ number_format($item['contracts_money'])}}</td>
                                    <td class="text-center middle">{{date('d-m-Y',$item['contracts_sign_day'])}}</td>
                                    <td class="text-center middle">{{date('d-m-Y',$item['contracts_effective_date'])}}</td>
                                    <td class="text-center middle">{{$item['contracts_describe']}}</td>
                                    <td class="text-center middle">
                                        @if($is_root== 1 || $personContracts_full== 1 || $personContracts_create == 1)
                                            <li>
                                                <a href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId($item['contracts_id'])))}}"
                                                   title="Sửa"><i class="fa fa-edit fa-2x"></i></a></li>
                                        @endif
                                        @if($is_root== 1 || $personContracts_full== 1 || $personContracts_delete == 1)
                                            <li><a class="deleteItem" title="Xóa"
                                                   onclick="HR.deleteItem('{{FunctionLib::inputId($item['contracts_id'])}}', WEB_ROOT + '/manager/department/deleteDepartment')"><i
                                                            class="fa fa-trash fa-2x"></i></a></li>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <tr>
                                <td colspan="8"> Chưa có dữ liệu</td>
                            </tr>
                        @endif
                    </table>
                    <a class="btn btn-success" href="{{URL::route('admin.user_view')}}"><i class="fa fa-reply"></i> Thêm mới hợp đồng</a>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop