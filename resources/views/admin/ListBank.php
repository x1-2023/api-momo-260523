<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$body = [
    'title' => 'Danh sách Tool'
];
$body['header'] = '
    <!-- DataTables -->
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
';
$body['footer'] = '
    <!-- DataTables  & Plugins -->
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/jszip/jszip.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/pdfmake/pdfmake.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/pdfmake/vfs_fonts.js"></script>   
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="'.BASE_URL('public/AdminLTE3/').'plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
';
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__.'/Navbar.php');
?>
<?php
if (isset($_POST['ThemNganHang']) && $getUser['level']==1) {
    $url_image = '';
    if (check_img('image') == true) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = 'public/assets/storage/images/bank'.$rand.'.png';
        $tmp_name = $_FILES['image']['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name,$uploads_dir);
        if ($addlogo) {
            $url_image = 'public/assets/storage/images/bank'.$rand.'.png';
        }
    }
    $isInsert = $NNL->insert("bank", [
        'image'         => $url_image,
        'short_name'    => check_string($_POST['short_name']),
        'accountNumber' => check_string($_POST['accountNumber']),
        'accountName'   => check_string($_POST['accountName'])
    ]);
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
    }
}
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-university mr-1"></i>
                                THÊM NGÂN HÀNG
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn bg-success btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-warning btn-sm" data-card-widget="maximize"><i
                                        class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn bg-danger btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Ngân hàng</label>
                                    <select class="form-control select2bs4" name="short_name" required>
                                        <option value="">Chọn ngân hàng</option>
                                        <option value="Vietcombank">Vietcombank</option>
                                        <option value="ACB">ACB</option>
                                        <option value="MBBank">MBBank</option>
                                        <option value="VPBank">VPBank</option>
                                        <option value="Techcombank">Techcombank</option>
                                        <option value="TPBank">TPBank</option>
                                        <option value="VPBank">VPBank</option>
                                        <option value="Vietinbank">Vietinbank</option>
                                        <option value="Sacombank">Sacombank</option>
                                        <option value="THESIEURE">THESIEURE</option>
                                        <option value="MOMO">MOMO</option>
                                        <option value="Viettelpay">Viettelpay</option>
                                        <option value="Zalo Pay">Zalo Pay</option>
                                        <option value="Cake">Cake</option>
                                        <option value="Shopee Pay">Shopee Pay</option>
                                        <option value="MSB">MSB</option>
                                        <option value="NamABank">NamABank</option>
                                        <option value="LienVietPostBank">LienVietPostBank</option>
                                        <option value="VietCapitalBank">VietCapitalBank</option>
                                        <option value="BIDV">BIDV</option>
                                        <option value="VIB">VIB</option>
                                        <option value="HDBank">HDBank</option>
                                        <option value="SeABank">SeABank</option>
                                        <option value="GPBank">GPBank</option>
                                        <option value="PVcomBank">PVcomBank</option>
                                        <option value="NCB">NCB</option>
                                        <option value="ShinhanBank">ShinhanBank</option>
                                        <option value="SCB">SCB</option>
                                        <option value="PGBank">PGBank</option>
                                        <option value="Agribank">Agribank</option>
                                        <option value="SaigonBank">SaigonBank</option>
                                        <option value="DongABank">DongABank</option>
                                        <option value="BacABank">BacABank</option>
                                        <option value="StandardChartered">StandardChartered</option>
                                        <option value="Oceanbank">Oceanbank</option>
                                        <option value="VRB">VRB</option>
                                        <option value="ABBANK">ABBANK</option>
                                        <option value="VietABank">VietABank</option>
                                        <option value="Eximbank">Eximbank</option>
                                        <option value="VietBank">VietBank</option>
                                        <option value="IndovinaBank">IndovinaBank</option>
                                        <option value="BaoVietBank">BaoVietBank</option>
                                        <option value="PublicBank">PublicBank</option>
                                        <option value="SHB">SHB</option>
                                        <option value="CBBank">CBBank</option>
                                        <option value="OCB">OCB</option>
                                        <option value="KienLongBank">KienLongBank</option>
                                        <option value="CIMB">CIMB</option>
                                        <option value="HSBC">HSBC</option>
                                        <option value="DBSBank">DBSBank</option>
                                        <option value="Nonghyup">Nonghyup</option>
                                        <option value="HongLeong">HongLeong</option>
                                        <option value="IBK Bank">IBK Bank</option>
                                        <option value="Woori">Woori</option>
                                        <option value="UnitedOverseas">UnitedOverseas</option>
                                        <option value="KookminHN">KookminHN</option>
                                        <option value="KookminHCM">KookminHCM</option>
                                        <option value="COOPBANK">COOPBANK</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="image" required>
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Số tài khoản</label>
                                    <input type="text" class="form-control" name="accountNumber"
                                        placeholder="Nhập số tài khoản" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên chủ tài khoản</label>
                                    <input type="text" class="form-control" name="accountName"
                                        placeholder="Nhập tên chủ tài khoản" required>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <button name="ThemNganHang" class="btn btn-info btn-icon-left m-b-10" type="submit"><i
                                        class="fas fa-plus mr-1"></i>Thêm Ngay</button>
                            </div>
                        </form>
                    </div>
                </section>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-1"></i>
                                DANH SÁCH NGÂN HÀNG
                            </h3>
                            <div class="card-tools">
                            <input type="hidden" value="<?=$getUser['token']?>" id="token">
                                <button type="button" class="btn bg-success btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-warning btn-sm" data-card-widget="maximize"><i
                                        class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn bg-danger btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table id="datatable1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5px;">STT</th>
                                            <th>Image</th>
                                            <th>ShortName</th>
                                            <th>Account Number</th>
                                            <th>Account Name</th>
                                            <th style="width: 20%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach ($NNL->get_list("SELECT * FROM `bank` ORDER BY `id` DESC") as $row) {?>
                                        <tr>
                                            <td><?=$i++?></td>
                                            <td width="10%">
                                                <img style="width:100%" src="<?=BASE_URL(''),$row['image']?>" alt="">
                                            </td>
                                            <td>
                                                <?=$row['short_name']?>
                                            </td>
                                            <td>
                                                <?=$row['accountNumber']?>
                                            </td>
                                            <td>
                                                <?=$row['accountName']?>
                                            </td>
                                            <td>
                                                <button style="color:white;" onclick="RemoveRow(<?=$row['id']?>)"
                                                    class="btn btn-danger btn-sm btn-icon-left m-b-10" type="button">
                                                    <i class="fas fa-trash mr-1"></i><span class="">Delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
function RemoveRow(id) {
    cuteAlert({
        type: "question",
        title: "Xác Nhận Xóa Bank",
        message: "Bạn có chắc chắn muốn xóa ID " + id + " không ?",
        confirmText: "Đồng Ý",
        cancelText: "Hủy"
    }).then((e) => {
        if (e) {
            $.ajax({
                url: "<?=BASE_URL('')?>ajaxs/admin/removeBank.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    token: $("#token").val()
                },
                success: function(respone) {
                    if (respone.status == 'success') {
                        cuteToast({
                            type: "success",
                            message: respone.msg,
                            timer: 5000
                        });
                        location.reload();
                    } else {
                        cuteAlert({
                            type: "error",
                            title: "Error",
                            message: respone.msg,
                            buttonText: "Okay"
                        });
                    }
                },
                error: function() {
                    alert(html(response));
                    location.reload();
                }
            });
        }
    })
}
</script>

<script>
$(function() {
    $('#datatable1').DataTable();
});
</script>
<script>
$(function() {
    $('#datatable2').DataTable();
});
</script>
<?php
require_once(__DIR__.'/Footer.php');
?>