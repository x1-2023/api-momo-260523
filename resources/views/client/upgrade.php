<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Gia hạn api | ' . $NNL->site('title');
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
                            <h5 class="m-b-10">Gia hạn API</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="#!">Gia hạn API</a></li>
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
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <img class="card-img-top" src="https://static.mservice.io/blogscontents/momo-upload-api-210330100757-637526956774717557.png" style="width: 250px; margin: 0 auto;">
                            <div class="card-body">
                                <h5 class="card-title mb-0 text-center">Hệ thống nâng cấp tài khoản</h5>
                                <p>An toàn - uy tín - chất lượng - nhanh chóng</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title mb-0 text-center">Nâng cấp gói API</h2>
                                <div style="color: red;" class="text-gray-700 dark:text-gray-400 px-10 text-center mx-auto mt-2">Sử dụng cho
                                    các
                                    cổng: Momo</div>
                                <div style="color: red;" class="text-gray-600 dark:text-gray-400 px-10 text-center mx-auto mt-2">Mỗi cổng
                                    thêm vào tối đa <?=$NNL->site("limit_api_momo")?> tài khoản</div>
                                <div style="color: red;" class="text-gray-600 dark:text-gray-400 px-10 text-center mx-auto mt-2">(Không giới
                                    hạn
                                    số lượng kiểm tra giao dịch)</div>
                            </div>
                            <div class="card-body text-center">
                                <h3 class=" mb-0 text-center"><?=format_cash($NNL->site("money_api_momo"))?> / 1 Tháng</h3>
                                <input type="hidden" id="token" value="<?=$getUser['token']?>">
                                <div class="text-xl font-medium text-center mt-2">Chọn thời gian nâng cấp</div>
                                <div style="color: red;" class="text-gray-600 dark:text-gray-400 px-10 text-center mx-auto mt-2"> <select class="form-control" data-live-search="true" name="thoigiangiahanvcb" id="thoigiangiahanmomo" onchange="tongtienmomo()">
                                        <option value="">Chọn thời gian nâng cấp</option>
                                        <option value="1">1 Tháng</option>
                                        <option value="2">2 Tháng</option>
                                        <option value="3">3 Tháng</option>
                                        <option value="4">4 Tháng</option>
                                        <option value="5">5 Tháng</option>
                                        <option value="6">6 Tháng</option>
                                    </select></span><br />
                                    <div id="tongtienmomo"></div><br />
                                    <button id="btnTransfergoimomo" class="btn btn-success">Nâng cấp ngay</button>
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
    $("#btnTransfergoimomo").click(function() {
        $('#btnTransfergoimomo').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled',
            true);
        $.ajax({
            url: '<?=BASE_URL('ajaxs/client/updatemomo.php')?>',
            type: 'POST',
            dataType: "json",
            data: {
                act: "giahanmomo",
                thoigiangiahanmomo: $('#thoigiangiahanmomo').val(),
                token: $('#token').val()
            },
            success: function(res) {
                if (res.status == '2') {
                    cuteToast({
                        type: "success",
                        message: res.msg,
                        timer: 5000
                    });
                    setTimeout(function() {
                        window.location = "<?=BASE_URL('client/upgrade')?>"
                    }, 1000);
                } else {
                    cuteToast({
                        type: "error",
                        message: res.msg,
                        timer: 5000
                    });
                }
                $('#btnTransfergoimomo').html('Nâng cấp ngay').prop('disabled', false);
            }
        });
    });

    function tongtienmomo() {
        var thoigiangiahanmomo = $('#thoigiangiahanmomo').val();
        if (thoigiangiahanmomo == '') {
            $('#tongtienmomo').html('');
        } else {
            $.ajax({
                url: '<?=BASE_URL('ajaxs/client/updatemomo.php')?>',
                type: 'POST',
                data: {
                    act: "totalmomo",
                    thoigiangiahanmomo: thoigiangiahanmomo,
                    token: $('#token').val()
                },
                success: function(result) {
                    $('#tongtienmomo').html(result);
                }
            });
        }

    }
</script>
<?php require_once __DIR__ . '/footer.php';?>