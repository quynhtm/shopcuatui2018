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
			<li class="active">Góp ý - Thắc mắc về hệ thống</li>
		</ul>
	</div>

	<div class="page-content">
		<div class="row">
			<div class="col-xs-12">
				@if(sizeof($data) > 0)
					<div class="box-body widget-box bd-blue" style="min-height: 144px; border: 1px solid #6fb3e0; border-radius: 5px">
						<div class="widget-header widget-header-flat infobox-blue infobox-dark" style="margin: 0px!important;">
							<h4 class="widget-title ng-binding">
								<i class="icon-tags"></i>
								Góp ý - Thắc mắc về hệ thống
							</h4>
						</div>
						<div class="widget-body">
							<ul class="link-dash">
								@foreach ($data as $key => $item)
									<li>{{ $stt+$key+1 }}. <a title="{{ stripcslashes($item->news_title) }}" href="{{FunctionLib::buildLinkDetailNewsSupport($item->news_id, $item->news_title)}}">{{ stripcslashes($item->news_title) }}</a></li>
								@endforeach
							</ul>
						</div>
					</div>
					@if($paging != '')
					<div class="text-right">
						{!! $paging !!}
					</div>
					@endif
				@endif
			</div>
		</div>
	</div>
</div>
@stop