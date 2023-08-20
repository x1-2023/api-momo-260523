<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thêm tài khoản momo | ' . $NNL->site('title');
$body['header'] = '

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
            <!-- [ stiped-table ] start -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Thông tin tài khoản momo</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label">Số điện thoại</label>
                                    <input type="number" class="form-control" id="phone" placeholder="Nhập chính xác số điện thoại">
                                    <input type="hidden" class="form-control" id="token" value="<?=$getUser['token']?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="Text">Mật khẩu</label>
                                    <input type="number" min="0" class="form-control" id="password" placeholder="Mật khẩu momo">
                                </div>
                            </div>
                            <div class="col-sm-12" style="display:none" id="otpinput">
                                <div class="form-group">
                                    <label class="floating-label">OTP</label>
                                    <input type="number" class="form-control" id="otp" placeholder="Nhập đúng otp">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="button" id="GetOTP" class="btn btn-danger w-100">Lấy OTP</button>
                                <button style="display:none" type="button" id="btnCheckOTP" class="btn btn-success w-100">Đăng Nhập</button>
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


<script>
    $("#GetOTP").on("click", function() {
        $('#GetOTP').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled',
            true);
        var myData = {
            action: 'GETOTP',
            sdt: $("#phone").val(),
            pass: $("#password").val(),
            token: $("#token").val()
        };
        $.post("<?=BASE_URL("ajaxs/client/momo.php");?>", myData,
            function(res) {
                if (res.status == '2') {
                    cuteToast({
                        type: "success",
                        message: res.msg,
                        timer: 5000
                    });
                    document.getElementById("otpinput").style.display = 'block';
                    document.getElementById("GetOTP").style.display = 'none';
                    document.getElementById("btnCheckOTP").style.display = 'block';
                } else {
                    cuteToast({
                        type: "error",
                        message: res.msg,
                        timer: 5000
                    });
                }
                $('#GetOTP').html('Lấy OTP').prop('disabled', false);
            }, "json");

    });
    $("#btnCheckOTP").on("click", function() {
        $('#btnCheckOTP').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled',
            true);
        var myOTPData = {
            action: 'CHECKOTP',
            sdt: $("#phone").val(),
            pass: $("#password").val(),
            otp: $("#otp").val()
        };
        $.post("<?=BASE_URL("ajaxs/client/momo.php");?>", myOTPData,
            function(data) {
                if (data.status == '2') {
                    cuteToast({
                        type: "success",
                        message: data.msg,
                        timer: 5000
                    });
                    setTimeout(function() {
                        window.location = "<?=BASE_URL('client/listaccount')?>"
                    }, 1000);
                } else {
                    cuteToast({
                        type: "error",
                        message: data.msg,
                        timer: 5000
                    });
                }
                $('#btnCheckOTP').html('Đăng Nhập').prop('disabled', false);
            }, "json");
    });
</script>

<?php require_once __DIR__ . '/footer.php';?>