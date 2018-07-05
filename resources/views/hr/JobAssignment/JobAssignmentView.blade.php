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
                <li class="active">Bổ nhiệm/ Bổ nhiệm lại chức vụ</li>
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
                        <div class="block_title">Bổ nhiệm/ Bổ nhiệm lại chức vụ</div>
                        <div id="div_list_bo_nhiem">
                            <div class="span clearfix"> @if(count($jobAssignment) >0) Có tổng số <b>{{count($jobAssignment)}}</b> bổ nhiệm @endif </div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="15%">Chức vụ được bổ nhiệm</th>
                                    <th width="10%" class="text-center">Ngày bổ nhiệm</th>
                                    <th width="10%" class="text-center">Số quyết định</th>
                                    <th width="10%" class="text-center">Thời gian bổ nhiệm</th>
                                    <th width="15%">Chức vụ cũ</th>
                                    <th width="10%" class="text-center">Trạng thái</th>
                                    <th width="20%">Ghi chú</th>
                                    <th width="5%" class="text-center">Bổ nhiệm</th>
                                    <th width="5%" class="text-center">Xóa</th>
                                </tr>
                                </thead>
                                @if(sizeof($jobAssignment) > 0)
                                    <tbody>
                                    @foreach ($jobAssignment as $key => $item)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrChucVu[$item['job_assignment_define_id_new']])){{ $arrChucVu[$item['job_assignment_define_id_new']] }}@endif</td>
                                            <td class="text-center middle"> @if($item['job_assignment_date_creater'] > 0){{ date('d-m-Y',$item['job_assignment_date_creater']) }}@endif</td>
                                            <td class="text-center middle">{{$item['job_assignment_code']}}</td>
                                            <td class="text-center middle">{{ date('d-m-Y',$item['job_assignment_date_start']) }} <br/>{{date('d-m-Y',$item['job_assignment_date_end'])}}</td>
                                            <td>@if(isset($arrChucVu[$item['job_assignment_define_id_old']])){{ $arrChucVu[$item['job_assignment_define_id_old']] }}@endif</td>
                                            <td class="text-center middle">
                                                @if($item['job_assignment_status'] == 1)
                                                    Đã bổ nhiệm
                                                @else
                                                    Đề xuất
                                                @endif
                                            </td>
                                            <td>
                                                {{$item['job_assignment_note']}}
                                            </td>
                                            <td class="text-center middle">
                                                @if($item['job_assignment_status'] == 1)
                                                    <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                                @else
                                                    <a class="deleteItem" title="Ẩn" style="color: red" onclick="HR.updateStatusAjaxCommon('{{FunctionLib::inputId($item['job_assignment_id'])}}',{{$item['job_assignment_status']}},'jobAssignment/updateStatus')">
                                                        <i class="fa fa-close fa-2x"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $jobAssignmentFull== 1 || $jobAssignmentDelete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['job_assignment_person_id'])}}','{{FunctionLib::inputId($item['job_assignment_id'])}}','jobAssignment/deleteJobAssignment','div_list_bo_nhiem',0)"><i class="fa fa-trash fa-2x"></i></a>
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
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','jobAssignment/editJobAssignment',{{\App\Library\AdminFunction\Define::JOBASSIGNMENT_THONG_BAO}})"><i class="fa fa-reply"></i> Thêm thông báo bổ nhiệm</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-warning" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','jobAssignment/editJobAssignment',{{\App\Library\AdminFunction\Define::JOBASSIGNMENT_DA_BO_NHIEM}})"><i class="fa fa-reply"></i> Thêm chức vụ đã bổ nhiệm</a>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop