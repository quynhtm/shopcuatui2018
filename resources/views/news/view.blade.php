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
			<li class="active">Danh sách tin tức</li>
		</ul>
	</div>

	<div class="page-content">
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				<div class="panel panel-info">
					<form method="Post" action="" role="form">
						{{ csrf_field() }}
						<div class="panel-body">
							<div class="form-group col-sm-2">
								<label for="user_name" class="control-label"><i>Từ khóa</i></label>
								<input type="text" class="form-control input-sm" id="news_title" name="news_title" @if(isset($search['news_title']))value="{{$search['news_title']}}"@endif>
							</div>
							<div class="form-group col-lg-3">
								<label for="user_group"><i>Trạng thái</i></label>
								<select name="news_status" id="news_status" class="form-control input-sm">
									{!! $optionStatus !!}
								</select>
							</div>
						</div>
						<div class="panel-footer text-right">
							<a class="btn btn-danger btn-sm" href="{{URL::route('admin.newsEdit',array('id' => FunctionLib::inputId(0)))}}">
								<i class="ace-icon fa fa-plus-circle"></i>
								Thêm mới
							</a>
						</div>
					</form>
				</div>
				@if(sizeof($data) > 0)
					<div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> tin tức @endif </div>
					<br>
					<table class="table table-bordered table-hover">
						<thead class="thin-border-bottom">
						<tr class="">
							<th width="2%" class="text-center">STT</th>
							<th width="30%">Tên bài viết</th>
							<th width="15%">Ngày tạo</th>
							<th width="8%" class="text-center">Chức năng</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($data as $key => $item)
							<tr class="middle">
								<td class="text-center middle">{{ $stt+$key+1 }}</td>
								<td class="text-left middle">{{ stripcslashes($item->news_title) }}</td>
								<td class="text-center middle">{{ date('d/m/Y', $item->news_created) }}</td>
								<td>
									@if($is_root || $permission_edit)
										<a href="{{URL::route('admin.newsEdit',array('id' => FunctionLib::inputId($item['news_id'])))}}" title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
									@endif
									@if($is_boss || $permission_remove)
										<a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['news_id'])}}', WEB_ROOT + '/manager/news/deleteNews')"><i class="fa fa-trash fa-2x"></i></a>
									@endif
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
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