<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Thông tin cá nhân | ' . $NNL->site('title');
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
                            <h5 class="m-b-10">Thông tin tài khoản</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Thông tin tài khoản</a></li>
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
                                <h5>Thông tin tài khoản</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label">Tài khoản đăng nhập</label>
                                            <input type="text" class="form-control" value="<?= $getUser['username'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label" for="Text">Số dư</label>
                                            <input type="text" class="form-control" value="<?= format_cash($getUser['money']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label">Ngày tham gia</label>
                                            <input type="text" class="form-control" value="<?= $getUser['create_date'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label">Hoạt động gần đây</label>
                                            <input type="text" class="form-control" value="<?= date('h:i:s d-m-Y', $getUser['time_session']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                            <input type="email" id="email" class="form-control" value="<?= $getUser['email'] ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary mr-2" id="changeEmail"><i class="fa fa-sync mr-1"></i> THAY ĐỔI</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Đổi mật khẩu</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label">Mật khẩu cũ</label>
                                            <input type="password" class="form-control" id="old_password">
                                            <input type="hidden" class="form-control" id="token" value="<?= $getUser['token'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label" for="Text">Mật khẩu mới</label>
                                            <input type="password" class="form-control" id="new_password">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="floating-label">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" id="confirm_new_password">
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-12">
                                    <button id="changePass" class="copy btn btn-primary w-100"><i class="fa fa-lock mr-1"></i> THAY ĐỔI</button>

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
    $("#changePass").on("click", function() {
        $('#changePass').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?=BASE_URL('ajaxs/client/changePassword.php');?>",
            method: "POST",
            dataType: "JSON",
            data: {
                token: $("#token").val(),
                action: "ChangePassword",
                password: $("#old_password").val(),
                newpassword: $("#new_password").val(),
                renewpassword: $("#confirm_new_password").val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    cuteToast({
                        type: "success",
                        message: respone.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '<?=BASE_URL('client/user-profile');?>';", 100);
                } else {
                    cuteToast({
                        type: "error",
                        message: respone.msg,
                        timer: 5000
                    });
                }
                $('#changePass').html('<i class="fas fa-lock"></i> THAY ĐỔI').prop('disabled',
                    false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#changePass').html('<i class="fas fa-lock"></i> THAY ĐỔI').prop('disabled',
                    false);
            }

        });
    });

    $("#changeEmail").on("click", function() {
        $('#changeEmail').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop(
            'disabled',
            true);
        $.ajax({
            url: "<?=BASE_URL('ajaxs/client/changeInfo.php');?>",
            method: "POST",
            dataType: "JSON",
            data: {
                token: $("#token").val(),
                email: $("#email").val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    cuteToast({
                        type: "success",
                        message: respone.msg,
                        timer: 5000
                    });
                    setTimeout("location.href = '<?=BASE_URL('client/user-profile');?>';", 100);
                } else {
                    cuteToast({
                        type: "error",
                        message: respone.msg,
                        timer: 5000
                    });
                }
                $('#changeEmail').html('<i class="fas fa-sync"></i> THAY ĐỔI').prop('disabled',
                    false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#changeEmail').html('<i class="fas fa-sync"></i> THAY ĐỔI').prop('disabled',
                    false);
            }

        });
    });
    </script>
<?php require_once __DIR__ . '/footer.php'; ?>