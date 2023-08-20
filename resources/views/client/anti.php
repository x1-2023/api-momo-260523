<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Anti trộm | ' . $NNL->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '
    
';
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
require_once(__DIR__ . '/../../../core/class/Momo.php');
error_reporting(0);
$Momo = new Momo;
?>
<?php
if (isset($_GET['phonecheck'])) {
    $rowData = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . xss($_GET['phonecheck']) . "' AND `user_id`='" . $getUser['username'] . "' ");
    if (!$rowData) {
        nnl_error_time("Liên kết không tồn tại", BASE_URL(''), 500);
    }
} else {
    nnl_error_time("Liên kết không tồn tại", BASE_URL(''), 0);
}

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
                            <h5 class="m-b-10">Lịch sử nhận tiền</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Lịch sử nhận tiền</a></li>
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
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Cấu hình anti</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Số điện thoại</label>
                                            <input type="number" id="phone" class="form-control" value="<?= $rowData['phone'] ?>" readonly>
                                            <input type="hidden" id="token" class="form-control" value="<?= $getUser['token'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">IP được phép chuyển tiền</label>
                                            <input type="text" id="ip" value="<?= $rowData['ip_white'] ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Trạng thái</label>
                                            <select class="form-control" id="status">
                                                <option value="1" <?= $rowData['status_ip_white'] == '1' ? 'selected' : '' ?>>Hoạt động</option>
                                                <option value="2" <?= $rowData['status_ip_white'] == '2' ? 'selected' : '' ?>>Tạm ngưng</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button class="btn btn-primary mr-2" id="anti"><i class="fa fa-sync mr-1"></i> THỰC HIỆN</button>
                                    </div>
                                </div>
                            </div>
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
    $("#anti").on("click", function() {
        $('#anti').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        var myData = {
            action: 'ANTI',
            phone: $("#phone").val(),
            ip: $("#ip").val(),
            status: $("#status").val(),
            token: $("#token").val(),
        };
        $.post("<?= BASE_URL("ajaxs/client/momo.php"); ?>", myData,
            function(data) {
                if (data.status == '2') {
                    cuteToast({
                        type: "success",
                        message: data.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '';", 1000);
                } else {
                    cuteToast({
                        type: "error",
                        message: data.msg,
                        timer: 5000
                    });
                }
                $('#anti').html('THỰC HIỆN').prop('disabled',
                    false);
            }, "json");

    });
</script>
<script>
    $('#datatable').DataTable({
        language: {
            url: "<?= BASE_URL('public/assets/Vietnamese.json') ?>"
        },
    });
</script>

<?php require_once(__DIR__ . '/footer.php'); ?>