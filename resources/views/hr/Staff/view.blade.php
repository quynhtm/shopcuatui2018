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
                <li class="active">Quản lý lương</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="panel panel-default">
                {{ csrf_field() }}
                <div class="panel-body-ns">
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <div class="line">
                                <div class="panel-heading clearfix">
                                    <h4 class="panel-title pull-left">BÁO CÁO DANH SÁCH VÀ TIỀN LƯƠNG CÔNG CHỨC 2018</h4>
                                    <div class="btn-group btn-group-sm pull-right">
                                        <span>
                                            <a href="{{URL::route('hr.exportDevice')}}" class="btn btn-default btn-sm">
                                                <i class="fa fa-file-excel-o"></i> Xuất ra file</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" action="" method="post" id="adminForm" name="adminForm">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label>Chọn Đơn vị/ Phòng ban</label>
                                                <select class="form-control input-sm" id="DepartmentSID" name="DepartmentSID"><option value="">- Đơn vị/ Phòng ban -</option>
                                                    <option value="5a660f2e473c2c9a98c0c4fd">Chỉnh sương ống chân</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Chọn năm báo cáo</label>
                                                <select class="required form-control input-sm" data-val="true" data-val-required="The ReportYear field is required." id="ReportYear" min="0" name="ReportYear"><option value="">- Chọn năm báo cáo -</option>
                                                    <option selected="selected">2018</option>
                                                    <option>2017</option>
                                                    <option>2016</option>
                                                    <option>2015</option>
                                                    <option>2014</option>
                                                    <option>2013</option>
                                                    <option>2012</option>
                                                    <option>2011</option>
                                                </select>
                                                <input id="hdAction" name="hdAction" value="" type="hidden">
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-area-chart"></i>&nbsp;Thống kê</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table style="width: 100%;" class="table table-bordered table-condensed">
                                                <tbody>
                                                <tr class="text-center">
                                                    <th rowspan="2">TT</th>
                                                    <th rowspan="2">Họ Và tên</th>
                                                    <th colspan="2">Ngày, tháng năm sinh</th>
                                                    <th rowspan="2">Chức vụ hoặc chức danh công tác</th>
                                                    <th rowspan="2">Cơ quan, đơn vị đang làm việc</th>
                                                    <th rowspan="2">Thời gian giữ ngạch (kể cả ngạch tương đương)</th>
                                                    <th colspan="2">Mức lương hiện hưởng</th>
                                                    <th colspan="5">Phụ cấp</th>
                                                    <th rowspan="2">Ghi chú</th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th>Nam</th>
                                                    <th>Nữ</th>
                                                    <th>Hệ số lương</th>
                                                    <th>Mã ngạch</th>
                                                    <th>Chức vụ</th>
                                                    <th>Trách nhiệm</th>
                                                    <th>Khu vực</th>
                                                    <th>Phụ cấp vượt khung</th>
                                                    <th>Tổng phụ cấp</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td class="text-nowrap">Nguyễn Thị B</td>
                                                    <td></td>
                                                    <td>29/03/2017</td>
                                                    <td>Trưởng phòng</td>
                                                    <td>Khoa duoc</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">4.4</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td class="text-nowrap">Nguyễn Thị C</td>
                                                    <td></td>
                                                    <td>01/01/1972</td>
                                                    <td>Trưởng phòng</td>
                                                    <td>Phòng vật tư xuất nhập khẩu</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">4.4</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td class="text-nowrap">Nguyễn Thị D</td>
                                                    <td></td>
                                                    <td>01/01/1973</td>
                                                    <td>Trưởng phòng</td>
                                                    <td>Khoa dieu duong</td>

                                                    <td>01/07/2017</td>
                                                    <td class="text-right">5.08</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td class="text-nowrap">Nguyễn Văn A</td>
                                                    <td>01/01/1975</td>
                                                    <td></td>
                                                    <td>Phó trưởng phòng</td>
                                                    <td>Khoa CDHA</td>

                                                    <td>01/07/2014</td>
                                                    <td class="text-right">4.4</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td class="text-nowrap">Lê Văn C</td>
                                                    <td>01/01/1975</td>
                                                    <td></td>
                                                    <td>Chuyên viên</td>
                                                    <td>Phòng tổ chức</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">2.67</td>
                                                    <td>01.003</td>

                                                    <td class="text-right">0.3</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0.2</td>
                                                    <td class="text-right">0.7</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td class="text-nowrap">Hoàng Huyền Trang</td>
                                                    <td></td>
                                                    <td>15/09/1994</td>
                                                    <td>Chuyên viên</td>
                                                    <td>Phòng tổ chức</td>

                                                    <td>01/02/2017</td>
                                                    <td class="text-right">2.67</td>
                                                    <td>01.003</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td class="text-nowrap">Lê Văn E</td>
                                                    <td>01/01/1960</td>
                                                    <td></td>
                                                    <td>Phó trưởng phòng</td>
                                                    <td>Phòng Kế toán</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">5.08</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td class="text-nowrap">Đặng Văn N</td>
                                                    <td>01/06/1957</td>
                                                    <td></td>
                                                    <td>Trưởng phòng</td>
                                                    <td>Phòng Kế toán</td>

                                                    <td>01/01/2015</td>
                                                    <td class="text-right">4.74</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td class="text-nowrap">Nguyễn Thị A</td>
                                                    <td></td>
                                                    <td>01/01/1970</td>
                                                    <td>Trưởng phòng</td>
                                                    <td>Khoa duoc</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">4.4</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td class="text-nowrap">Nguyễn Văn B</td>
                                                    <td>01/01/1982</td>
                                                    <td></td>
                                                    <td>Chuyên viên</td>
                                                    <td>Khoa chấn thương chỉnh hình</td>

                                                    <td>01/05/2014</td>
                                                    <td class="text-right">3</td>
                                                    <td>01.003</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td>11</td>
                                                    <td class="text-nowrap">trương mạnh quỳnh</td>
                                                    <td>22/01/2018</td>
                                                    <td></td>
                                                    <td>Phó trưởng phòng</td>
                                                    <td>Khoa CDHA</td>

                                                    <td>01/09/2010</td>
                                                    <td class="text-right">6.56</td>
                                                    <td>01.001</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop