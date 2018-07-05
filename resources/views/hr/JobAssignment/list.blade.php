<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
        <th width="20%" class="text-center">Ghi chú</th>
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
                <td class="text-center middle">@if(isset($arrChucVu[$item['job_assignment_define_id_old']])){{ $arrChucVu[$item['job_assignment_define_id_old']] }}@endif</td>
                <td class="text-center middle">
                    @if($item['job_assignment_status'] == 1)
                        Đã bổ nhiệm
                    @else
                        Đề xuất
                    @endif
                </td>
                <td class="text-center middle">
                    {{$item['job_assignment_note']}}
                </td>
                <td class="text-center middle">
                    @if($item['job_assignment_status'] == 1)
                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                    @else
                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
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