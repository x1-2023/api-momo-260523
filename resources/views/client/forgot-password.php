<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = "Quên mật khẩu";
$body['header'] = '
  
';
$body['footer'] = '
  
';
require_once(__DIR__ . '/header.php');
?>
<div class="auth-wrapper">
    <div class="auth-content text-center">
        <div class="card borderless">
            <div class="row align-items-center ">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="mb-3 f-w-400">KHÔI PHỤC MẬT KHẨU</h4>
                        <hr>
                        <div class="form-group mb-3">
                            <input class="form-control" placeholder="Email" type="email" id="email">
                        </div>
                        <button class="btn btn-block btn-primary mb-4" id="forgot">XÁC THỰC</button>
                        <hr>
                        <p class="mb-0 text-muted">Chưa có tài khoản? <a href="<?= BASE_URL('client/register') ?>" class="f-w-400">Đăng Ký</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#forgot").on("click", function() {
        $('#forgot').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?= BASE_URL('ajaxs/client/resetPassword.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                action: "forgotPassword",
                email: $("#email").val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    cuteToast({
                        type: "success",
                        message: respone.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '<?= BASE_URL('client/reset-password'); ?>';", 1000);
                } else {
                    cuteToast({
                        type: "error",
                        message: respone.msg,
                        timer: 5000
                    });
                }
                $('#forgot').html('XÁC THỰC').prop('disabled', false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#forgot').html('XÁC THỰC').prop('disabled', false);
            }
        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>