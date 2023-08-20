<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = "Đặt lại mật khẩu";
$body['header'] = '
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
';
$body['footer'] = '

';
require_once __DIR__ . '/header.php';
?>
<div class="auth-wrapper">
    <div class="auth-content text-center">
        <div class="card borderless">
            <div class="row align-items-center ">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="mb-3 f-w-400">ĐẶT LẠI MẬT KHẨU</h4>
                        <hr>
                        <div class="form-group mb-3">
                            <input class="form-control" placeholder="OTP..." type="number" id="otp">
                        </div>
                        <div class="form-group mb-4">
                            <input class="form-control" placeholder="Mật khẩu mới" type="password" id="password">
                        </div>
                        <div class="form-group mb-4">
                            <input class="form-control" placeholder="Xác nhận lại mật khẩu mới" type="password" id="repassword">
                        </div>

                        <button type="button" id="reset" class="btn btn-primary w-100">Đặt lại mật khẩu!</button>
                        <hr>
                        <p class="mb-0 text-muted">Đã có tài khoản? <a href="<?=BASE_URL('client/login')?>" class="f-w-400">Đăng Nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $("#reset").on("click", function() {
            $('#reset').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
                'disabled',
                true);
            $.ajax({
                url: "<?=BASE_URL('ajaxs/client/resetPassword.php');?>",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "resetPassword",
                    otp: $("#otp").val(),
                    password: $("#password").val(),
                    repassword: $("#repassword").val()
                },
                success: function(respone) {
                    if (respone.status == 'success') {
                        cuteToast({
                            type: "success",
                            message: respone.msg,
                            timer: 5000
                        });
                        setTimeout("location.href = '<?=BASE_URL('client/login');?>';", 1000);
                    } else {
                        cuteToast({
                            type: "error",
                            message: respone.msg,
                            timer: 5000
                        });
                    }
                    $('#reset').html('Đặt lại mật khẩu!').prop('disabled', false);
                },
                error: function() {
                    cuteToast({
                        type: "error",
                        message: 'Không thể xử lý',
                        timer: 5000
                    });
                    $('#reset').html('Đặt lại mật khẩu!').prop('disabled', false);
                }

            });
        });
    </script>
<?php require_once __DIR__ . '/footer.php';?>