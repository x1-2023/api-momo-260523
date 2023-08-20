<?php
$title = 'Lịch sử nhận tiền momo | ' . $NNL->site('title');
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
    if ($rowData) {
        $Momo->config = $rowData;
        $Momo->config['password'] = decodecryptData($rowData['password']);
        if ($Momo->config['TimeLogin'] < time() - 3600) {
            $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
            $extra = $result["extra"];
            $NNL->update("cron_momo", [
                'authorization' => $extra["AUTH_TOKEN"],
                'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                'sessionkey' => $extra["SESSION_KEY"],
                'errorDesc' => $result["errorCode"],
                'TimeLogin'  => time()
            ], " `phone` = '" . $Momo->config['phone'] . "' ");
        }
        if ($Momo->config['TimeLogin'] < time() - 3600) {
            $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
            $extra = $result["extra"];
            $NNL->update("cron_momo", [
                `authorization` => $extra["AUTH_TOKEN"],
                `RSA_PUBLIC_KEY` => $extra["REQUEST_ENCRYPT_KEY"],
                `sessionkey` => $extra["SESSION_KEY"],
                `errorDesc` => $result["errorCode"],
                `TimeLogin`  => time()
            ], " `phone` = '" . $Momo->config['phone'] . "' ");
        }
        $history = $Momo->CheckHistoryV2(1,10);
        $result = json_decode($history, true);
    } else {
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
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <div class="justify-content-start">
                                    <h5>Lịch sử nhận tiền của: <?= xss($_GET['phonecheck']) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table w-100" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">THỜI GIAN</th>
                                        <th class="whitespace-nowrap">LOẠI GIAO DỊCH</th>
                                        <th class="text-center whitespace-nowrap">MÃ GIAO DỊCH</th>
                                        <th class="text-center whitespace-nowrap">SỐ ĐIỆN THOẠI</th>
                                        <th class="text-center whitespace-nowrap">NGƯỜI CHUYỂN</th>
                                        <th class="text-center whitespace-nowrap">SỐ TIỀN</th>
                                        <th class="text-center whitespace-nowrap">NỘI DUNG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result['momoMsg']['tranList'] as $value) { ?>
                                        <tr class="intro-x">
                                            <td>
                                                <div><?= date('d-m-Y H:i:s', ($value['millisecond']) / 1000) ?>
                                                </div>
                                            </td>
                                            <td>Nhận tiền</td>
                                            <td style="color:green">
                                                <div><?= $value['tranId'] ?></div>
                                            </td>
                                            <td><?php if (isset($value['partnerId'])) {
                                                    echo $value['partnerId'];
                                                } ?>
                                            </td>
                                            <td><?php if (isset($value['partnerName'])) {
                                                    echo $value['partnerName'];
                                                } ?>
                                            </td>
                                            <td style="color:green"><?= format_cash($value['amount']) ?>đ</td>
                                            <td><?php if (isset($value['comment'])) {
                                                    echo $value['comment'];
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
     $('#datatable').DataTable({
        language: {
            url: "<?= BASE_URL('public/assets/Vietnamese.json') ?>"
        },
    });
</script>
<?php require_once(__DIR__ . '/footer.php'); ?>