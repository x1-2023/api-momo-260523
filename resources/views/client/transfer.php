<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Trang chủ | ' . $NNL->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
CheckLogin();
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/../../../core/class/Momo.php';
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
            <!-- [ stiped-table ] start -->
            <div class="col-xl-12">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Thông tin chuyển</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Chuyển đến số điện thoại</label>
                                            <input type="number" id="phone" onkeyup="getName()" class="form-control" placeholder="Nhập số cần chuyển">
                                            <input type="hidden" id="from" class="form-control" value="<?=$rowData['phone']?>">
                                            <input type="hidden" id="token" class="form-control" value="<?=$getUser['token']?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Người nhận tiền</label>
                                            <input type="text" id="namemomo" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Số tiền cần chuyển</label>
                                            <input type="number" id="money" class="form-control" placeholder="Nhập số tiền">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Mật khẩu momo</label>
                                            <input type="number" id="pass" class="form-control" placeholder="Nhập mật khẩu">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Nội dung</label>
                                            <textarea class="form-control" id="content" placeholder="Nội dung" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary mr-2" id="send"><i class="fa fa-sync mr-1"></i> XÁC NHẬN CHUYỂN</button>
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
    $("#send").on("click", function() {
        $('#send').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        var myData = {
            action: 'SENDMONEY',
            from: $("#from").val(),
            pass: $("#pass").val(),
            phone: $("#phone").val(),
            money: $("#money").val(),
            content: $("#content").val(),
            token: $("#token").val(),
        };
        $.post("<?=BASE_URL("ajaxs/client/momo.php");?>", myData,
            function(data) {
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
                $('#send').html('<i class="fas fa-sign-in-alt"></i> XÁC NHẬN CHUYỂN').prop('disabled',
                    false);
            }, "json");

    });

    function getName() {
        $.ajax({
            url: "<?=BASE_URL("ajaxs/client/momo.php");?>",
            method: "POST",
            dataType: "json",
            data: {
                action: 'GETNAME',
                phone: $("#phone").val(),
                token: $("#token").val(),
            },
            success: function(data) {
                if (data.status == '2') {
                    $("#namemomo").attr('value', data.msg);
                } else if (data.status == '1') {
                    $("#namemomo").attr('value', data.msg);
                }
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không tìm thấy người dùng momo',
                    timer: 5000
                });
            }
        });

    };
</script>

<?php require_once __DIR__ . '/footer.php';?>