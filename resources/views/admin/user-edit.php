<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Chỉnh sửa thành viên'
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
<?php
if(isset($_GET['id']) && $getUser['level']==1)
{
    $row = $NNL->get_row(" SELECT * FROM `users` WHERE `id` = '".xss($_GET['id'])."'  ");
    if(!$row)
    {
        die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
    }
}
else
{
    die('<script type="text/javascript">if(!alert("Không tồn tại !")){location.href = "javascript:history.back()";}</script>');
}
if(isset($_POST['Save']) && $getUser['level']==1)
{
    $isInsert= $NNL->update("users", array(
        'username'       => check_string($_POST['username']),
        'level'       => check_string($_POST['admin']),
    ), " `id` = '".$row['id']."' ");
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Lưu thành công!")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
if (isset($_POST['cong_tien']) && $getUser['level']==1) {
    if ($_POST['amount'] <= 0) {
        die('<script type="text/javascript">if(!alert("Amount không hợp lệ !")){window.history.back().location.reload();}</script>');
    }
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    /* Xử lý cộng tiền */
    PlusCredits($row['id'], $amount, $reason);
    die('<script type="text/javascript">if(!alert("Cộng tiền thành công !")){window.history.back().location.reload();}</script>');
}
if (isset($_POST['tru_tien']) && $getUser['level']==1) {
    if ($_POST['amount'] <= 0) {
        die('<script type="text/javascript">if(!alert("Amount không hợp lệ !")){window.history.back().location.reload();}</script>');
    }
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    /* Xử lý trừ tiền */
    RemoveCredits($row['id'], $amount, $reason);
    die('<script type="text/javascript">if(!alert("Trừ tiền thành công !")){window.history.back().location.reload();}</script>');
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <section class="col-lg-6">
                <div class="mb-3">
                    <a class="btn btn-danger btn-icon-left m-b-10" href="<?=BASE_URL('Admin/ListTool')?>"
                        type="button"><i class="fas fa-undo-alt mr-1"></i>Quay Lại</a>
                </div>
            </section>
            <section class="col-lg-6">
            </section>
            <section class="col-lg-12 connectedSortable">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-1"></i>
                            CHỈNH SỬA THÀNH VIÊN
                        </h3>
                        <div class="card-tools">
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
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tài khoản</label>
                                <input type="text" class="form-control" name="username" value="<?=$row['username']?>"
                                    placeholder="Nhập tài khoản" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" class="form-control" value="<?=$row['email']?>"
                                    name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Phân quyền</label>
                                <input type="text" class="form-control" value="<?=$row['level']?>" name="admin"
                                    placeholder="Nếu muốn admin thì nhập: admin" required>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <button name="Save" class="btn btn-info btn-icon-left m-b-10" type="submit"><i
                                    class="fas fa-save mr-1"></i>Lưu Ngay</button>
                        </div>
                    </form>
                </div>
            </section>
            <section class="col-lg-6 connectedSortable">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-money-bill-alt mr-1"></i>
                            CỘNG TIỀN
                        </h3>
                        <div class="card-tools">
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
                        <form class="" action="" method="POST" role="form">
                            <div class="form-group">
                                <label>Amount (*)</label>
                                <input type="hidden" class="form-control" id="id" value="<?=$user['id'];?>">
                                <input type="number" class="form-control" name="amount"
                                    placeholder="Nhập số tiền cần cộng" required>
                            </div>
                            <div class="form-group">
                                <label>Reason (*)</label>
                                <textarea class="form-control" name="reason"
                                    placeholder="Nhập nội dung nếu có"></textarea>
                            </div>
                            <br>
                            <button aria-label="" name="cong_tien" class="btn btn-info btn-icon-left m-b-10"
                                type="submit"><i class="fas fa-paper-plane mr-1"></i>Submit</button>
                        </form>
                    </div>
                </div>
            </section>
            <section class="col-lg-6 connectedSortable">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-money-bill-alt mr-1"></i>
                            TRỪ TIỀN
                        </h3>
                        <div class="card-tools">
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
                        <form class="" action="" method="POST" role="form">
                            <div class="form-group">
                                <label>Amount (*)</label>
                                <input type="hidden" class="form-control" id="id" value="<?=$user['id'];?>">
                                <input type="number" class="form-control" name="amount"
                                    placeholder="Nhập số tiền cần trừ" required>
                            </div>
                            <div class="form-group">
                                <label>Reason (*)</label>
                                <textarea class="form-control" name="reason"
                                    placeholder="Nhập nội dung nếu có"></textarea>
                            </div>
                            <br>
                            <button aria-label="" name="tru_tien" class="btn btn-info btn-icon-left m-b-10"
                                type="submit"><i class="fas fa-paper-plane mr-1"></i>Submit</button>
                        </form>
                    </div>
                </div>
            </section>
            <section class="col-lg-12 connectedSortable">
                <div class="card card-primary card-outline">
                    <div class="card-header ">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i>
                            LỊCH SỬ DÒNG TIỀN
                        </h3>
                        <div class="card-tools">
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
                                        <th scope="col">STT</th>
                                        <th scope="col">Số tiền trước</th>
                                        <th scope="col">Số dư thay đổi</th>
                                        <th scope="col">Số tiền hiện tại</th>
                                        <th scope="col">Nội dung</th>
                                        <th scope="col">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($NNL->get_list("SELECT * FROM `log_balance` WHERE `user_id`='".$row['id']."' ORDER BY `id` DESC") as $row){?>
                                    <tr>
                                        <td><?=$i++?></td>
                                        <td><?=format_cash($row['money_before'])?>đ</td>
                                        <td><?=format_cash($row['money_change'])?>đ</td>
                                        <td><?=format_cash($row['money_after'])?>đ</td>
                                        <td><?=$row['content']?></td>
                                        <td><?=$row['time']?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-lg-12 connectedSortable">
                <div class="card card-primary card-outline">
                    <div class="card-header ">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i>
                            NHẬT KÝ HOẠT ĐỘNG
                        </h3>
                        <div class="card-tools">
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
                            <table id="datatable2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tài khoản</th>
                                        <th>Hoạt động</th>
                                        <th>Thời gian</th>
                                        <th>IP</th>
                                        <th>Thiết bị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; foreach($NNL->get_list("SELECT * FROM `logs` WHERE `user_id`='".$row['id']."' ORDER BY `id` DESC") as $row){?>
                                    <tr>
                                        <td><?=$i++?></td>
                                        <td><?=getUser($row['user_id'], 'username');?></td>
                                        <td><?=$row['action']?></td>
                                        <td><?=$row['create_date']?></td>
                                        <td><?=$row['ip']?></td>
                                        <td><?=$row['device']?></td>
                                    </tr>
                                    <?php }?>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
<script>
$(function() {
    $('#datatable1').DataTable();
});
$(function() {
    $('#datatable2').DataTable();
});
</script>
<?php 
    require_once(__DIR__."/Footer.php");
?>