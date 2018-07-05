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
            <li class="active">Quản văn bản</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="hr_document_name" class="control-label"><i>Tên văn bản</i></label>
                                <input type="text" class="form-control input-sm" id="hr_document_name" name="hr_document_name" autocomplete="off" placeholder="Tên văn bản" @if(isset($dataSearch['hr_document_name']))value="{{$dataSearch['hr_document_name']}}"@endif>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Cơ quan ban hành</i></label>
                                <select name="hr_document_promulgate" id="hr_document_promulgate" class="form-control input-sm" tabindex="12" data-placeholder="Cơ quan ban hành">
                                    {!! $optionPromulgate !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Loại văn bản</i></label>
                                <select name="hr_document_type" id="hr_document_type" class="form-control input-sm" tabindex="12" data-placeholder="Loại văn bản">
                                    {!! $optionType !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Lĩnh vực</i></label>
                                <select name="hr_document_field" id="hr_document_field" class="form-control input-sm" tabindex="12" data-placeholder="Lĩnh vực">
                                    {!! $optionField !!}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                    <span class="">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrDocumentEdit',array('id' => FunctionLib::inputId(0)))}}">
                            <i class="ace-icon fa fa-plus-circle"></i>
                            Thêm mới
                        </a>
                    </span>
                            <span class="">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                    </span>
                        </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> văn bản đến @endif </div>
                    <br>
                    <div class="list-view-file">
                        <div class="line-head">
                            <div class="row">
                                <div class="col-lg-2 text-bold">Số/ký hiệu</div>
                                <div class="col-lg-8 text-bold">Tên văn bản</div>
                                <div class="col-lg-2 text-bold">Ngày ban hành</div>
                            </div>
                        </div>
                        @foreach($data as $k=>$item)
                        <?php
                        $class = ($item->hr_document_status == Define::mail_chua_doc) ? 'textBold' : '';
                        ?>
                        <div class="one-item-file">
                            <div class="line-detail-desc">
                               <div class="row">
                                   <div class="col-lg-2 {{$class}}">{{$item->hr_document_code}}</div>
                                   <div class="col-lg-8 {{$class}}">{{$item->hr_document_name}}</div>
                                   <div class="col-lg-2 {{$class}}">
                                       {{date('d/m/Y', $item->hr_document_date_issued)}} &nbsp;&nbsp;&nbsp;&nbsp;

                                       <a class="rlt3 iclick" href="{{URL::route('hr.HrDocumentViewItemGet',array('id' => FunctionLib::inputId($item['hr_document_id'])))}}" title="Xem"><i class="fa fa-eye fa-2x"></i></a>

                                       @if($is_boss || $permission_remove)
                                           <a class="deleteItem rlt2 iclick" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['hr_document_id'])}}', WEB_ROOT + '/manager/document/deleteHrDocument')"><i class="fa fa-trash fa-2x"></i></a>
                                       @endif
                                   </div>
                               </div>
                            </div>
                            <div class="item-detail-content">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="_p">
                                            <table class="table borderless table-condensed">
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Số/ký hiệu</th>
                                                    <td>{{$item->hr_document_code}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Cơ quan ban hành</th>
                                                    <td>
                                                        @if(isset($arrPromulgate[$item['hr_document_promulgate']]) && $arrPromulgate[$item['hr_document_promulgate']] != -1)
                                                            {{$arrPromulgate[$item['hr_document_promulgate']]}}
                                                        @else
                                                            Chưa xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Loại văn bản</th>
                                                    <td>
                                                        @if(isset($arrType[$item['hr_document_type']]) && $arrType[$item['hr_document_type']] != -1)
                                                            {{$arrType[$item['hr_document_type']]}}
                                                        @else
                                                            Chưa xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Lĩnh vực</th>
                                                    <td>
                                                        @if(isset($arrField[$item['hr_document_field']]) && $arrField[$item['hr_document_field']] != -1)
                                                            {{$arrField[$item['hr_document_field']]}}
                                                        @else
                                                            Chưa xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Người ký</th>
                                                    <td>
                                                        @if($item->hr_document_signer != '')
                                                            {{$item->hr_document_signer}}
                                                        @else
                                                            Không xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Ngày Ban hành</th>
                                                    <td>
                                                        @if($item->hr_document_date_issued > 0)
                                                            {{date('d/m/Y', $item->hr_document_date_issued)}}
                                                        @else
                                                            Không xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Ngày Hiệu lực</th>
                                                    <td>
                                                        @if($item->hr_document_effective_date > 0)
                                                            {{date('d/m/Y', $item->hr_document_effective_date)}}
                                                        @else
                                                            Không xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Ngày hết Hiệu lực</th>
                                                    <td>
                                                        @if($item->hr_document_date_expired > 0)
                                                            {{date('d/m/Y', $item->hr_document_date_expired)}}
                                                        @else
                                                            Không xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Ngày đưa tin</th>
                                                    <td>
                                                        @if($item->hr_document_delease_date > 0)
                                                            {{date('d/m/Y', $item->hr_document_delease_date)}}
                                                        @else
                                                            Không xác định
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="_f">
                                            <hr class="visible-xs-block">
                                            <div class="file-download col-xs-12 ">
                                                @if(isset($item->hr_document_files) && $item->hr_document_files !='')
                                                <?php $arrfiles = unserialize($item->hr_document_files); ?>
                                                <p><strong class="text-lowercase">{{count($arrfiles)}} Tệp kèm theo:</strong></p>
                                                <div class="media">
                                                    <div class="media-left media-middle">
                                                        <img class="media-object" src="{{url('/')}}/assets/admin/img/icon-doc.png">
                                                    </div>
                                                    <div class="media-body">
                                                        @foreach($arrfiles as $_key=>$file)
                                                            <div class="ite">
                                                                <p class="media-heading text-capitalize">{{$file}}</p>
                                                                <span class="medium">{{$_key+1}}.</span> <a target="_blank" class="btn-link" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_DOCUMENT.'/'.$item->hr_document_id.'/'.$file}}">Tải về</a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-right">
                        {!! $paging !!}
                    </div>
                @else
                    <div class="alert">
                        Không có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop