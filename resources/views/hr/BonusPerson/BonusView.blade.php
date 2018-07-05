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
                <li class="active">Thông tin khen thưởng</li>
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
                        <div class="block_title">KHEN THƯỞNG</div>
                        <div id="div_list_khenthuong">
                            <div class="span clearfix"> @if(count($khenthuong) >0) Có tổng số <b>{{count($khenthuong)}}</b> khen thưởng @endif </div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="20%">Thành tích</th>
                                    <th width="10%" class="text-center">Năm đạt</th>
                                    <th width="20%" class="text-center">Quyết định đính kèm</th>
                                    <th width="10%" class="text-center">Thưởng</th>
                                    <th width="27%" class="text-center">Ghi chú</th>
                                    <th width="8%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($khenthuong) > 0)
                                    <tbody>
                                    @foreach ($khenthuong as $key => $item)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item['bonus_year'] }}</td>
                                            <td class="text-center middle">{{$item['bonus_decision']}}</td>
                                            <td class="text-center middle">{{ number_format($item['bonus_number'])}}</td>
                                            <td class="text-center middle">{{$item['bonus_note']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $personBonusFull== 1 || $personBonusView == 1)
                                                    <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"title="Sửa"><i class="fa fa-eye fa-2x"></i></a>
                                                @endif
                                                @if($is_root== 1 || $personBonusFull== 1 || $personBonusDelete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/deleteBonus','div_list_khenthuong',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-trash fa-2x"></i></a>
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
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-reply"></i> Thêm mới khen thưởng</a>
                    </div>

                    {{----danh hiệu--}}
                    <div class="marginTop20">
                        <div class="block_title">DANH HIỆU</div>
                        <div id="div_list_danhhieu">
                            <div class="span clearfix"> @if(count($danhhieu) >0) Có tổng số <b>{{count($danhhieu)}}</b> danh hiệu @endif </div>
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="5%" class="text-center">STT</th>
                                <th width="30%">Danh hiệu</th>
                                <th width="10%" class="text-center">Năm đạt</th>
                                <th width="20%" class="text-center">Quyết định đính kèm</th>
                                <th width="27%" class="text-center">Ghi chú</th>
                                <th width="8%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($danhhieu) > 0)
                                <tbody>
                                @foreach ($danhhieu as $key2 => $item2)
                                    <tr>
                                        <td class="text-center middle">{{ $key2+1 }}</td>
                                        <td>@if(isset($arrTypeDanhhieu[$item2['bonus_define_id']])){{ $arrTypeDanhhieu[$item2['bonus_define_id']] }}@endif</td>
                                        <td class="text-center middle"> {{ $item2['bonus_year'] }}</td>
                                        <td class="text-center middle">{{$item2['bonus_decision']}}</td>
                                        <td class="text-center middle">{{$item2['bonus_note']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personBonusFull== 1 || $personBonusView == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_DANH_HIEU}})"title="Sửa"><i class="fa fa-eye fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personBonusFull== 1 || $personBonusView == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item2['bonus_person_id'])}}','{{FunctionLib::inputId($item2['bonus_id'])}}','bonusPerson/deleteBonus','div_list_danhhieu',{{\App\Library\AdminFunction\Define::BONUS_DANH_HIEU}})"><i class="fa fa-trash fa-2x"></i></a>
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
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_DANH_HIEU}})"><i class="fa fa-reply"></i> Thêm mới danh hiệu</a>
                    </div>

                    {{----Kỷ luật--}}
                    <div class="marginTop20">
                        <div class="block_title">KỶ LUẬT</div>
                        <div id="div_list_kyluat">
                            <div class="span clearfix"> @if(count($kyluat) >0) Có tổng số <b>{{count($kyluat)}}</b> kỷ luật @endif </div>
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="5%" class="text-center">STT</th>
                                <th width="30%">Hình thức</th>
                                <th width="10%" class="text-center">Năm bị kỷ luật</th>
                                <th width="20%" class="text-center">Quyết định đính kèm</th>
                                <th width="27%" class="text-center">Ghi chú</th>
                                <th width="8%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($kyluat) > 0)
                                <tbody>
                                @foreach ($kyluat as $key3 => $item3)
                                    <tr>
                                        <td class="text-center middle">{{ $key3+1 }}</td>
                                        <td>@if(isset($arrTypeKyluat[$item3['bonus_define_id']])){{ $arrTypeKyluat[$item3['bonus_define_id']] }}@endif</td>
                                        <td class="text-center middle">{{ $item3['bonus_year'] }}</td>
                                        <td class="text-center middle">{{$item3['bonus_decision']}}</td>
                                        <td class="text-center middle">{{$item3['bonus_note']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personBonusFull== 1 || $personBonusView == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KY_LUAT}})"title="Sửa"><i class="fa fa-eye fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personBonusFull== 1 || $personBonusView == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item3['bonus_person_id'])}}','{{FunctionLib::inputId($item3['bonus_id'])}}','bonusPerson/deleteBonus','div_list_kyluat',{{\App\Library\AdminFunction\Define::BONUS_KY_LUAT}})"><i class="fa fa-trash fa-2x"></i></a>
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
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KY_LUAT}})"><i class="fa fa-reply"></i> Thêm mới kỷ luật</a>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop