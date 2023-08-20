<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Trang chủ | ' . $NNL->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
CheckLogin();
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
?>
<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Bảng điều khiển</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Bảng điều khiển</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3><?= format_cash($NNL->num_rows("SELECT * FROM `account_momo` where `user_id`='" . $getUser['username'] . "'")) ?></h3>
                                <h6 class="text-muted m-b-0">Tổng số momo</h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3><?= format_cash($getUser['money']) ?>đ</h3>
                                <h6 class="text-muted m-b-0">Số dư tài khoản</h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3><?= format_cash($getUser['total_money']) ?>đ</h3>
                                <h6 class="text-muted m-b-0">Tổng tiền nạp</h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3><?= format_cash($NNL->get_row("SELECT COUNT(id) FROM `send` WHERE `date_time` >= DATE(NOW()) AND `date_time` < DATE(NOW()) + INTERVAL 1 DAY ")['COUNT(id)']); ?></h3>
                                <h6 class="text-muted m-b-0">Bank hôm nay</h6>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ stiped-table ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Hoạt động gần đây</h5>

                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table w-100" id="datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hoạt động</th>
                                        <th>IP</th>
                                        <th>Thiết bị</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($NNL->get_list("SELECT * FROM `logs` WHERE `user_id` = '" . $getUser['id'] . "' ORDER BY `id` DESC LIMIT 200") as $row) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['action'] ?></td>
                                            <td><?= $row['ip'] ?></td>
                                            <td><?= $row['device'] ?></td>
                                            <td><?= $row['create_date'] ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ stiped-table ] end -->

        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>
<?php if($NNL->site('status_noti')==1):?>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Thông Báo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <?=$NNL->site('notification')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn  btn-danger" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
     $(document).ready(function() {
        $("#myModal").modal("show");
    });
</script>
<?php endif?>
<script type="text/javascript">
    $('#datatable').DataTable({
        language: {
            url: "<?= BASE_URL('public/assets/Vietnamese.json') ?>"
        },
    });
</script>
<?php
if ($getUser['time_momo'] < time()) {
?>
    <script>
        cuteAlert({
            type: "error",
            title: "Thông Báo",
            message: "Tài khoản của bạn đã hết hạn, Vui lòng nâng cấp để xài vĩnh viễn",
            confirmText: "Đồng Ý",
        })
    </script>
<?php
}
?>
<?php require_once __DIR__ . '/footer.php'; ?>