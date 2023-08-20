<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh sách Tool'
];
$body['header'] = '
    <!-- DataTables -->
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
';
$body['footer'] = '
    <!-- DataTables  & Plugins -->
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/jszip/jszip.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/pdfmake/pdfmake.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/pdfmake/vfs_fonts.js"></script>   
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
';
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__.'/Navbar.php');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tổng thành viên</span>
                            <span class="info-box-number"><?=format_cash($NNL->num_rows("SELECT * FROM `users`"))?>
                                thành viên</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Số dư thành viên</span>
                            <span
                                class="info-box-number"><?=format_cash($NNL->get_row("SELECT SUM(`money`) FROM `users` ")['SUM(`money`)']);?>đ</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-cog"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Staff</span>
                            <span class="info-box-number">Admin:
                                <?=format_cash($NNL->num_rows("SELECT * FROM `users` WHERE `level`='admin'"))?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-lock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tài khoản bị vô hiệu hoá</span>
                            <span class="info-box-number">
                                <?=format_cash($NNL->num_rows("SELECT * FROM `users` WHERE `banned`='1'"))?> tài
                                khoản</span>
                        </div>
                    </div>
                </div>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i>
                                DANH SÁCH THÀNH VIÊN
                            </h3>
                            <div class="card-tools">
                            <input type="hidden" value="<?=$getUser['token']?>" id="token">
                                <button type="button" class="btn bg-success btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-warning btn-sm" data-card-widget="maximize"><i
                                        class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn bg-danger btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table id="datatable1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5px;">STT</th>
                                            <th>Tài khoản</th>
                                            <th>Ví</th>
                                            <th>Bảo mật</th>
                                            <th style="width: 20%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach ($NNL->get_list("SELECT * FROM `users` ORDER BY `id` DESC") as $row) {?>
                                        <tr>
                                            <td><?=$i++?></td>
                                            <td>
                                                <ul>
                                                    <li>Tên đăng nhập: <b><?=$row['username']?></b>
                                                        [<b><?=$row['id']?></b>]</li>
                                                    <li>Địa chỉ Email: <b style="color:green">
                                                            [<?=$row['email']?>]</b>
                                                    </li>
                                                    <li>Vai trò: <?=admin($row['level'])?>
                                                    </li>
                                                    <li>Tình trạng: <span
                                                            class="badge badge-success"><?=display_banned($row['banned'])?></span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>Số dư khả dụng: <b
                                                            style="color:blue"><?=format_cash($row['money'])?>đ</b>
                                                    </li>
                                                    <li>Tổng số tiền nạp: <b
                                                            style="color:red"><?=format_cash($row['total_money'])?>đ</b>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>IP: <b><?=$row['ip']?></b></li>
                                                    <li>Status: <b><?=display_online($row['time_session'])?></b>
                                                    </li>
                                                    <li>Ngày tham gia: <b><?=$row['create_date']?></b></li>
                                                    <li>Hoạt động gần đây:
                                                        <b><?=date('H:i:s d-m-Y',$row['time_session'])?></b>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <a aria-label=""
                                                    href="<?=BASE_URL('admin/user-edit/')?><?=$row['id']?>"
                                                    style="color:white;"
                                                    class="btn btn-info btn-sm btn-icon-left m-b-10" type="button">
                                                    <i class="fas fa-edit mr-1"></i><span class="">Edit</span>
                                                </a>
                                                <button style="color:white;" onclick="RemoveRow(<?=$row['id']?>)"
                                                    class="btn btn-danger btn-sm btn-icon-left m-b-10" type="button">
                                                    <i class="fas fa-trash mr-1"></i><span class="">Delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
function RemoveRow(id) {
    cuteAlert({
        type: "question",
        title: "Xác Nhận Xóa Thành Viên",
        message: "Bạn có chắc chắn muốn xóa thành viên ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeUser.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(respone) {
                    if (respone.status == 'success') {
                        cuteToast({
                            type: "success",
                            message: respone.msg,
                            timer: 5000
                        });
                        location.reload();
                    } else {
                        cuteAlert({
                            type: "error",
                            title: "Error",
                            message: respone.msg,
                            buttonText: "Okay"
                        });
                    }
                },
                error: function() {
                    alert(html(response));
                    location.reload();
                }
            });
        }
    })
}
</script>

<script>
$(function() {
    $('#datatable1').DataTable();
});
</script>
<script>
$(function() {
    $('#datatable2').DataTable();
});
</script>
<?php
require_once(__DIR__.'/Footer.php');
?>
<script type="text/javascript">
$.ajax({
    url: "<?=BASE_URL('update.php');?>",
    type: "GET",
    dateType: "text",
    data: {},
    success: function(result) {

    }
});
</script>