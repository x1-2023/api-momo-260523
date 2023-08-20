<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = "Đăng nhập";
$body['header'] = '

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
						<h4 class="mb-3 f-w-400">HỆ THỐNG API TỰ ĐỘNG</h4>
						<hr>
						<div class="form-group mb-3">
							<input type="text" class="form-control" id="username" placeholder="Tên đăng nhập">
						</div>
						<div class="form-group mb-4">
							<input type="password" class="form-control" id="password" placeholder="Mật khẩu">
						</div>
					
						<button class="btn btn-block btn-primary mb-4" id="btnLogin">Đăng Nhập</button>
						<hr>
						<p class="mb-2 text-muted">Quên mật khẩu? <a href="<?=BASE_URL('client/forgot-password')?>" class="f-w-400">Khôi Phục</a></p>
						<p class="mb-0 text-muted">Chưa có tài khoản? <a href="<?=BASE_URL('client/register')?>" class="f-w-400">Đăng Ký</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

    <script type="text/javascript">
    $("#btnLogin").on("click", function() {
        $('#btnLogin').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?=base_url('ajaxs/client/login.php');?>",
            method: "POST",
            dataType: "JSON",
            data: {
                username: $("#username").val(),
                password: $("#password").val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    cuteToast({
                        type: "success",
                        message: respone.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '<?=BASE_URL('');?>';", 100);
                } else {
                    cuteToast({
                        type: "error",
                        message: respone.msg,
                        timer: 5000
                    });
                }
                $('#btnLogin').html('<i class="fas fa-sign-in-alt"></i> Đăng Nhập').prop('disabled',
                    false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#btnLogin').html('<i class="fas fa-sign-in-alt"></i> Đăng Nhập').prop('disabled',
                    false);
            }

        });
    });
    </script>
<?php require_once __DIR__ . '/footer.php';?>