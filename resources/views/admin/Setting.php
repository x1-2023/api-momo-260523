<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Dashboard'
];
$body['header'] = '
    <!-- DataTables -->
    <link rel="stylesheet" href="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
';
$body['footer'] = '
    <!-- DataTables  & Plugins -->
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/jszip/jszip.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/pdfmake/pdfmake.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/pdfmake/vfs_fonts.js"></script>   
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="' . BASE_URL('public/AdminLTE3/') . 'plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
';
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
require_once(__DIR__ . '/Header.php');
require_once(__DIR__ . '/Sidebar.php');
require_once(__DIR__ . '/Navbar.php');
?>
<?php
if (isset($_POST['SaveSettings']) && $getUser['level']==1) {
    foreach ($_POST as $key => $value) {
        $NNL->update("settings", array(
            'value' => $value
        ), " `name` = '$key' ");
    }
    die('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
} ?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12">
                    <div class="card card-dark card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">THÔNG TIN CHUNG</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tab3" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">MOMO AUTO</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Title</label>
                                                    <input type="text" class="form-control" name="title" value="<?= $NNL->site('title') ?>" placeholder="VD: sieuthicode.net">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Description</label>
                                                    <input type="text" class="form-control" name="description" value="<?= $NNL->site('description') ?>" placeholder="VD: Hệ thống bán mã nguồn website MMO uy tín">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Keywords</label>
                                                    <input type="text" class="form-control" name="keywords" value="<?= $NNL->site('keywords') ?>" placeholder="VD: api momo..">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Author</label>
                                                    <input type="text" class="form-control" name="author" value="<?= $NNL->site('author') ?>" placeholder="VD: Nguyễn Nhật Lộc">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Hotline</label>
                                                    <input type="text" class="form-control" name="hotline" value="<?= $NNL->site('hotline') ?>" placeholder="Số điện thoại liên hệ">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="email" class="form-control" name="email" value="<?= $NNL->site('email') ?>" placeholder="Email liên hệ">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email SMTP</label>
                                                    <input type="email" class="form-control" name="email_smtp" value="<?= $NNL->site('email_smtp') ?>" placeholder="Nhập địa chỉ Email SMTP">
                                                    <i>Hướng dẫn cấu hình SMTP <a target="_blank" href="https://www.youtube.com/watch?v=aiMScMCqMIg">tại
                                                            đây</a></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Password Email SMTP</label>
                                                    <input type="text" class="form-control" name="pass_email_smtp" value="<?= $NNL->site('pass_email_smtp') ?>" placeholder="Nhập mật khẩu Email">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Thời gian lưu phiên đăng
                                                        nhập</label>
                                                    <input type="number" class="form-control" name="session_login" value="<?= $NNL->site('session_login') ?>" placeholder="Nhập thời gian lưu phiên đăng nhập">
                                                    <i>Tính bằng giây (2592000 =
                                                        4 tuần)</i>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Link facebook
                                                    </label>
                                                    <input type="text" class="form-control" name="link_facebook" value="<?= $NNL->site('link_facebook') ?>">

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Link zalo
                                                    </label>
                                                    <input type="text" class="form-control" name="link_zalo" value="<?= $NNL->site('link_zalo') ?>">

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Số ngày cho mấy thanh niên test API
                                                    </label>
                                                    <input type="number" class="form-control" name="time_test_api" value="<?= $NNL->site('time_test_api') ?>" placeholder="Nhập thời gian test">
                                                    <i>Tính bằng giây (86400 =
                                                        1 ngày)</i>
                                                </div>

                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Status thông báo</label>
                                                    <select class="form-control select2bs4" name="status_noti">
                                                        <option value="1" <?= $NNL->site('status_noti') == '1' ? 'selected' : '' ?>>
                                                            Bật
                                                        </option>
                                                        <option value="0" <?= $NNL->site('status_noti') == '0' ? 'selected' : '' ?>>
                                                            Tắt
                                                        </option>
                                                    </select>
                                                    <i>Chọn OFF hệ thống sẽ tạm dừng thông báo.</i>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Thông báo website</label>
                                                    <textarea type="text" class="form-control" id="thongbao" name="notification"><?= $NNL->site('notification') ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <button name="SaveSettings" class="btn btn-info btn-icon-left m-b-10" type="submit"><i class="fas fa-save mr-1"></i>Lưu Ngay</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control select2bs4" name="status_momo">
                                                <option value="1" <?= $NNL->site('status_momo') == '1' ? 'selected' : '' ?>>
                                                    Bật
                                                </option>
                                                <option value="0" <?= $NNL->site('status_momo') == '0' ? 'selected' : '' ?>>
                                                    Tắt
                                                </option>
                                            </select>
                                            <i>Chọn OFF hệ thống sẽ tạm dừng auto momo.</i>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Token MOMO (<a type="button" data-toggle="modal" data-target="#modal-hd-auto-momo" href="#">Xem
                                                    hướng dẫn</a>)</label>
                                            <input type="text" class="form-control" name="token_momo" value="<?= $NNL->site('token_momo') ?>" placeholder="Nhập token ví momo">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nội dung nạp</label>
                                            <input type="text" class="form-control" name="noidungnap_momo" value="<?= $NNL->site('noidungnap_momo') ?>" placeholder="Nội dung nạp">
                                        </div>
                                        <button name="SaveSettings" class="btn btn-info btn-icon-left m-b-10" type="submit"><i class="fas fa-save mr-1"></i>Lưu Ngay</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
</div>


<script>
    $('#thongbao').summernote({
        placeholder: 'Điền nội dung',
        tabsize: 2,
        height: 400,
    });
</script>
<?php
require_once(__DIR__ . '/Footer.php');
?>