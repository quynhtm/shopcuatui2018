<?php use App\Library\AdminFunction\CGlobal; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\FunctionLib; ?>
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
                <li class="active">Thông tin hộ chiếu - mã số thuế</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form method="POST" action="" role="form">
                        @if(isset($error))
                            <div class="alert alert-danger" role="alert">
                                @foreach($error as $itmError)
                                    <p>{!! $itmError !!}</p>
                                @endforeach
                            </div>
                    @endif
                    <!--Block 1--->
                        <div class="form-group">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số hộ chiếu phổ thông<span class="red"> (*) </span></label>
                                    <input type="text" id="passport_common" name="passport_common"  class="form-control input-sm" value="@if(isset($data['passport_common'])){{$data['passport_common']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày cấp</label>
                                    <input type="text" class="form-control" id="passport_common_date_range" name="passport_common_date_range"  data-date-format="dd-mm-yyyy" value="@if(isset($data['passport_common_date_range']) && $data['passport_common_date_range'] > 0){{date('d-m-Y',$data['passport_common_date_range'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Có giá trị đến</label>
                                    <input type="text" class="form-control" id="passport_common_date_expiration" name="passport_common_date_expiration"  data-date-format="dd-mm-yyyy" value="@if(isset($data['passport_common_date_expiration']) && $data['passport_common_date_expiration'] > 0){{date('d-m-Y',$data['passport_common_date_expiration'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nơi cấp</label>
                                    <input type="text" id="passport_common_address_range" name="passport_common_address_range"  class="form-control input-sm" value="@if(isset($data['passport_common_address_range'])){{$data['passport_common_address_range']}}@endif">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số hộ chiếu công vụ</label>
                                    <input type="text" id="passport_equitment" name="passport_equitment"  class="form-control input-sm" value="@if(isset($data['passport_equitment'])){{$data['passport_equitment']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày cấp</label>
                                    <input type="text" class="form-control" id="passport_equitment_date_range" name="passport_equitment_date_range"  data-date-format="dd-mm-yyyy" value="@if(isset($data['passport_equitment_date_range']) && $data['passport_equitment_date_range'] > 0){{date('d-m-Y',$data['passport_equitment_date_range'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Có giá trị đến</label>
                                    <input type="text" class="form-control" id="passport_equitment_date_expiration" name="passport_equitment_date_expiration"  data-date-format="dd-mm-yyyy" value="@if(isset($data['passport_equitment_date_expiration']) && $data['passport_equitment_date_expiration'] > 0){{date('d-m-Y',$data['passport_equitment_date_expiration'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nơi cấp</label>
                                    <input type="text" id="passport_equitment_address_range" name="passport_equitment_address_range"  class="form-control input-sm" value="@if(isset($data['passport_equitment_address_range'])){{$data['passport_equitment_address_range']}}@endif">
                                </div>
                            </div>

                            <div class="clear"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Mã số thuế cá nhân</label>
                                    <input type="text" id="passport_personal_code" name="passport_personal_code"  class="form-control input-sm" value="@if(isset($data['passport_personal_code'])){{$data['passport_personal_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số tài khoản ngân hàng</label>
                                    <input type="text" id="passport_bank_account_number" name="passport_bank_account_number"  class="form-control input-sm" value="@if(isset($data['passport_bank_account_number'])){{$data['passport_bank_account_number']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngân hàng</label>
                                    <input type="text" id="passport_bank_account" name="passport_bank_account"  class="form-control input-sm" value="@if(isset($data['passport_bank_account'])){{$data['passport_bank_account']}}@endif">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            {!! csrf_field() !!}
                            <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var passport_common_date_range = $('#passport_common_date_range').datepicker({ });
            var passport_common_date_expiration = $('#passport_common_date_expiration').datepicker({ });
            var passport_equitment_date_range = $('#passport_equitment_date_range').datepicker({ });
            var passport_equitment_date_expiration = $('#passport_equitment_date_expiration').datepicker({ });
        });
    </script>
@stop