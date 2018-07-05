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
                <li class="active">Thông tin lương phụ cấp</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}
                    @if(isset($infoPerson))
                        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
                    @endif

                    <div class="marginTop20">
                        <div class="block_title">LƯƠNG</div>
                        <div id="div_list_lương">
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="30%">Nghạch /Bậc</th>
                                    <th width="10%" class="text-center">Hệ số lương</th>
                                    <th width="20%" class="text-center">Lương cơ sở</th>
                                    <th width="10%" class="text-center">Tháng năm</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($lương) > 0)
                                    <tbody>
                                    @foreach ($lương as $key => $item)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrNgachBac[$item['salary_civil_servants']])){{ $arrNgachBac[$item['salary_civil_servants']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item['salary_coefficients'] }}</td>
                                            <td class="text-center middle">{{number_format($item['salary_salaries'])}}</td>
                                            <td class="text-center middle">{{$item['salary_month']}}/{{$item['salary_year']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceCreate == 1)
                                                    <a class="viewItem" title="Chi tiết lương" onclick="HR.getInfoSalaryPopup('{{FunctionLib::inputId($item['salary_id'])}}')">
                                                        <i class="fa fa-eye fa-2x"></i>
                                                    </a>
                                                @endif
                                                @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceCreate == 1)
                                                    <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item['salary_person_id'])}}','{{FunctionLib::inputId($item['salary_id'])}}','salaryAllowance/editSalary',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                                @endif
                                                @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceDelete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['salary_person_id'])}}','{{FunctionLib::inputId($item['salary_id'])}}','salaryAllowance/deleteSalary','div_list_lương',0)"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','salaryAllowance/editSalary',0)"><i class="fa fa-reply"></i> Thêm mới lương</a>
                    </div>

                    <div class="marginTop20">
                        <div class="block_title">PHỤ CẤP</div>
                        <div id="div_list_phucap">
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="30%">Loại phụ cấp</th>
                                    <th width="20%">Phụ cấp trả theo hình thức</th>
                                    <th width="10%" class="text-center">Hệ số, giá trị</th>
                                    <th width="10%" class="text-center">Tháng năm</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($phucap) > 0)
                                    <tbody>
                                    @foreach ($phucap as $key => $item2)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrOptionPhuCap[$item2['allowance_type']])){{ $arrOptionPhuCap[$item2['allowance_type']] }}@endif</td>
                                            <td>@if(isset($arrMethodPhuCap[$item2['allowance_method_type']])){{ $arrMethodPhuCap[$item2['allowance_method_type']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item2['allowance_method_value'] }}</td>
                                            <td class="text-center middle">{{$item2['allowance_month_start']}}/{{$item2['allowance_year_start']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceCreate == 1)
                                                    <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item2['allowance_person_id'])}}','{{FunctionLib::inputId($item2['allowance_id'])}}','salaryAllowance/editAllowance',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                                @endif
                                                @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceDelete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item2['allowance_person_id'])}}','{{FunctionLib::inputId($item2['allowance_id'])}}','salaryAllowance/deleteAllowance','div_list_phucap',0)"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','salaryAllowance/editAllowance',0)"><i class="fa fa-reply"></i> Thêm mới phụ cấp</a>
                    </div>

                    <div class="marginTop20">
                        <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                        <a class="btn btn-primary"  href="/manager/infoPerson/viewContracts/{{FunctionLib::inputId($person_id)}}"><i class="fa fa-forward"></i> Lưu và tiếp tục</a>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop