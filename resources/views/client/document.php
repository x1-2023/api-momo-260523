<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Tài liệu API | ' . $NNL->site('title');
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
                            <h5 class="m-b-10">Tài liệu API</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Tài liệu API</a></li>
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
                        <h5 class="card-title">URL API</h5>
                        <div class="input-group mb-3">
                            <input type="text" id="url" class="form-control" value="<?= BASE_URL('api/historymomo') ?>" readonly>
                            <div class="input-group-append">
                                <button class="copy btn btn-primary mr-2" onclick="copy()" data-clipboard-target="#url">COPY</button>
                                <a href="<?= BASE_URL('API.zip') ?>" class="btn btn-dark">Tài Liệu</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="tblRequest">
                            <thead>
                                <tr>
                                    <th>
                                        Tham số
                                    </th>
                                    <th>
                                        Dữ liệu
                                    </th>
                                    <th>
                                        Ví dụ
                                    </th>
                                    <th>
                                        Chú thích
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b class="text-danger">history</b></td>
                                    <td>string</td>
                                    <td>39D6670A-1B9A-A12B-ADB0-DB020B35F5CF</td>
                                    <td>Token của tài khoản momo cần POST</td>
                                </tr>
                            </tbody>
                        </table> <br />
                        <div class="bg-light p-2 text-danger">
                            Response
                            <pre><code class="php">
{
    "status": true,
    "message": "Thành công",
    "momoMsg": {
        "tranList": [
            {
                "tranId": 23643551872,
                "id": "1651314554074_01657385033",
                "partnerId": "0931999671",
                "partnerName": "ĐẶNG THỊ OANH",
                "comment": "6575",
                "amount": 640,
                "millisecond": 1651314554074
            },
            {
                "tranId": 23637631827,
                "id": "1651297613132_01657385033",
                "partnerId": "01677890408",
                "partnerName": "Ng Huynh Kim Ngan",
                "comment": "5874",
                "amount": 4400,
                "millisecond": 1651297613132
            }
        ]
    }
}
                            </code></pre>
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