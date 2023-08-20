<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = "Đăng ký";
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
                        <h4 class="mb-3 f-w-400">ĐĂNG KÝ TÀI KHOẢN</h4>
                        <hr>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" id="username" placeholder="Tên đăng nhập">
                        </div>
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" class="form-control" id="password" placeholder="Mật khẩu">
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" class="form-control" id="repassword" placeholder="Xác nhận mật khẩu">
                        </div>

                        <button class="btn btn-block btn-primary mb-4" id="btnRegister">Đăng Ký</button>
                        <hr>
                       
                        <p class="mb-0 text-muted">Đã có tài khoản? <a href="<?= BASE_URL('client/login') ?>" class="f-w-400">Đăng Nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#btnRegister").on("click", function() {
        $('#btnRegister').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled',
            true);
        $.ajax({
            url: "<?= BASE_URL('ajaxs/client/register.php'); ?>",
            method: "POST",
            dataType: "JSON",
            data: {
                username: $("#username").val(),
                email: $("#email").val(),
                password: $("#password").val(),
                repassword: $("#repassword").val(),
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    cuteToast({
                        type: "success",
                        message: respone.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '<?= BASE_URL(''); ?>';", 100);
                } else {
                    cuteToast({
                        type: "error",
                        message: respone.msg,
                        timer: 5000
                    });
                }
                $('#btnRegister').html('Đăng Ký').prop('disabled', false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#btnRegister').html('Đăng Ký').prop('disabled', false);
            }

        });
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>