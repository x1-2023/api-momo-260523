<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Danh sách tài khoản Momo | ' . $NNL->site('title');
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
                            <h5 class="m-b-10">Danh sách tài khoản momo</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Danh sách tài khoản momon</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ stiped-table ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <div class="justify-content-start">
                                    <h5>Tài khoản Momo</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <a href="<?=BASE_URL('client/add-account')?>" class="btn btn-success btn-sm">Thêm Momo</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table w-100" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Tên tài khoản</th>
                                        <th>Số điện thoại</th>
                                        <th>Số dư</th>
                                        <th>Thời gian thêm</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($NNL->get_list("SELECT * FROM `account_momo` WHERE `user_id`='" . $getUser['username'] . "'") as $row) { ?>
                                        <tr>
                                            <td><?= $row['Name'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= format_cash(getMoney_momo($row['setupKeyDecrypt'])) ?>đ</td>
                                            <td><?= date('h:i:s d/m/Y', $row['TimeLogin']) ?></td>
                                            <td class="table-action">
                                                <input type="hidden" class="form-control" id="token" value="<?= $getUser['token'] ?>">
                                                <a href="<?= BASE_URL('client/anti/'), $row['phone'] ?>"><button class="btn btn-secondary btn-xs" type="button"><i class="fa fa-eye"></i> Anti Trộm</button></a>
                                                <a href="<?= BASE_URL('client/viewhis/'), $row['phone'] ?>"><button class="btn btn-success btn-xs" type="button"><i class="fa fa-list"></i> Lịch sử nhận tiền</button></a>
                                                <a href="<?= BASE_URL('client/viewsend/'), $row['phone'] ?>"><button class="btn btn-dark btn-xs" type="button"><i class="fa fa-upload"></i> Lịch sử chuyển tiền</button></a>
                                                <a href="<?= BASE_URL('client/transfer/'), $row['phone'] ?>"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-location-arrow"></i> Chuyển tiền</button></a>
                                                <a href="<?= BASE_URL('client/sendbank/'), $row['phone'] ?>"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-download"></i> Rút về bank</button></a>
                                                <button class="btn btn-warning btn-xs" onclick="GetToken(<?= $row['id'] ?>)" type="button"><i class="fa fa-power-off"></i> Lấy Token</button>
                                                <button class="btn btn-danger btn-xs" onclick="DeleteMomo(<?= $row['id'] ?>)" type="button"><i class="fa fa-trash"></i> Xóa</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
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

<script type="text/javascript">
    $('#datatable').DataTable({
        language: {
            url: "<?= BASE_URL('public/assets/Vietnamese.json') ?>"
        },
    });

    function DeleteMomo(id) {
        cuteAlert({
            type: "question",
            title: "Xác nhận xóa tài khoản",
            message: "Bạn có chắc chắn muốn xóa không ?",
            confirmText: "Đồng Ý",
            cancelText: "Huỷ"
        }).then((e) => {
            if (e) {
                $.ajax({
                    type: "post",
                    url: "<?= BASE_URL("ajaxs/client/actionmomo.php"); ?>",
                    dataType: "json",
                    data: {
                        atc: "DELETEMOMO",
                        id: id,
                        token: $("#token").val()
                    },
                    success: function(data) {
                        if (data.status == '2') {
                            cuteToast({
                                type: "success",
                                message: data.msg,
                                timer: 5000
                            });
                            setTimeout(function() {
                                window.location = "<?= BASE_URL('client/listaccount') ?>"
                            }, 1000);
                        } else {
                            cuteToast({
                                type: "error",
                                message: data.msg,
                                timer: 5000
                            });
                        }
                    }
                });
            }
        })
    }

    function GetToken(id) {
        cuteAlert({
            type: "question",
            title: "Xác nhận lấy token",
            message: "Bạn có chắc chắn muốn lấy token qua Email không ?",
            confirmText: "Đồng Ý",
            cancelText: "Huỷ"
        }).then((e) => {
            if (e) {
                $.ajax({
                    type: "post",
                    url: "<?= BASE_URL("ajaxs/client/actionmomo.php"); ?>",
                    dataType: "json",
                    data: {
                        atc: "TOKENMOMO",
                        id: id,
                        token: $("#token").val()
                    },
                    success: function(data) {
                        if (data.status == '2') {
                            cuteToast({
                                type: "success",
                                message: data.msg,
                                timer: 5000
                            });
                        } else {
                            cuteToast({
                                type: "error",
                                message: data.msg,
                                timer: 5000
                            });
                        }
                    },
                });
            }
        })
    }
</script>
<?php require_once __DIR__ . '/footer.php'; ?>