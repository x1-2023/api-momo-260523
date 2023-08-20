<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Nạp tiền | ' . $NNL->site('title');
$body['header'] = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
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
                            <h5 class="m-b-10">Nạp tiền</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Nạp tiền</a></li>
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
                    <?php foreach ($NNL->get_list("SELECT * FROM `bank`") as $row) { ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div style="height: 150px;">
                                        <img src="<?= BASE_URL(''), $row['image'] ?>" class="mx-auto" width="100%" height="100%">
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item">Số tài khoản: <b id="copySTK11" style="color: green;"><?= $row['accountNumber'] ?></b> <button onclick="copy()" data-clipboard-target="#copySTK11" class="copy btn btn-primary btn-sm"><i class="fas fa-copy"></i></button>
                                        </li>
                                        <li class="list-group-item">Chủ tài khoản: <b><?= $row['accountName'] ?></b>
                                        </li>
                                        <li class="list-group-item">Ngân hàng: <b><?= $row['short_name'] ?></b></li>
                                        <li class="list-group-item">Nội dung nạp: <b id="copyNoiDung11" style="color: red;"><?= $NNL->site('noidungnap_momo'), $getUser['id'] ?></b>
                                            <button onclick="copy()" data-clipboard-target="#copyNoiDung11" class="copy btn btn-primary btn-sm"><i class="fas fa-copy"></i></button>
                                        </li>
                                    </ul>
                                    <center><i><i class="fa fa-spinner fa-spin"></i> Xử lý giao dịch tự động trong vài
                                            giây...</i></center>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <!-- [ stiped-table ] end -->

        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>

<script>
    new ClipboardJS(".copy");

    function copy() {
        cuteToast({
            type: "success",
            message: "Đã sao chép vào bộ nhớ tạm",
            timer: 5000
        });
    }
</script>
<?php require_once(__DIR__ . '/footer.php'); ?>