<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
require_once('../../core/class/class.smtp.php');
require_once('../../core/class/PHPMailerAutoload.php');
require_once('../../core/class/class.phpmailer.php');

if ($_POST['act'] == 'totalmomo') {
    if (empty($_POST['token'])) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    $thoigiangiahanmomo = xss((int)$_POST['thoigiangiahanmomo']);
    $money = $NNL->site("money_api_momo");
    $totalmomo = $money * $thoigiangiahanmomo;
    echo '<input type="text" id="Name" class="form-control t14 RoR" placeholder="" value="' . format_cash($totalmomo) . ' VNĐ" disabled="disabled">';
}
if ($_POST['act'] == 'giahanmomo') {
    $thoigiangiahanmomo = xss((int)$_POST['thoigiangiahanmomo']);
    if (empty($_POST['token'])) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (empty($thoigiangiahanmomo)) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng chọn thời gian cần nâng cấp!']));
    }
    if ($thoigiangiahanmomo < 1) {
        die(json_encode(['status' => '1', 'msg' => 'Thời gian không hợp lệ!']));
    }
    $time = time();
    $money = $NNL->site("money_api_momo");
    $countDay =  $thoigiangiahanmomo * 30;
    if ($getUser['time_momo'] < $time) {
        $timeto = $time + 86400 * $countDay;
        $giatien = $money * $thoigiangiahanmomo;
        // exit(json_encode(array('status' => '1', 'msg' => 'hết hạn!')));
    } else {
        $timeto = $getUser['time_momo'] + 86400 * $countDay;
        $giatien = $money * $thoigiangiahanmomo;
        // exit(json_encode(array('status' => '2', 'msg' => 'còn hạn!')));
    }
    //kiểm tra số dư so với giá tiền thuê
    if ($getUser['money'] < $giatien) {
        die(json_encode(['status' => '1', 'msg' => 'Bạn không đủ ' . format_cash($giatien) . ' VNĐ để nâng cấp gói api']));
    } else {
        $isBuy = RemoveCredits($getUser['id'], $giatien, "Nâng cấp gói api momo #" . $giatien);
        if ($isBuy) {
            if (getRowRealtime("users", $getUser['id'], "money") < 0) {
                Banned($getUser['id'], 'Gian lận khi nâng cấp gói api');
                die(json_encode(['status' => '1', 'msg' => 'Bạn đã bị khoá tài khoản vì gian lận']));
            }
            /* LƯU HOẠT ĐỘNG LẠI */
            $NNL->insert("logs", [
                'user_id'       => $getUser['id'],
                'ip'            => myip(),
                'device'        => $_SERVER['HTTP_USER_AGENT'],
                'create_date'    => gettime(),
                'action'        => 'Nâng cấp gói api momo (#' . $giatien . ')'
            ]);
            $NNL->update("users", [
                'time_momo' => $timeto
            ], " `username` = '" . $getUser['username'] . "' ");
            die(json_encode(['status' => '2', 'msg' => 'Gia hạn api thành công']));
        }
    }
}
if (isset($_POST['id']) && $_POST['atc'] == 'TOKENMOMO') {
    if (empty($_POST['token'])) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    $id = xss($_POST['id']);
    if (empty($id)) {
        exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền thông tin!')));
    }
    $row = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `id` = '$id' AND `user_id`='" . $getUser['username'] . "' ");
    if (!$row) {
        exit(json_encode(array('status' => '1', 'msg' => 'Tài khoản thẻ siêu rẻ không tồn tại!')));
    }
    $user = $NNL->get_row(" SELECT * FROM `users` WHERE `username` = '" . $row['user_id'] . "' ");
    if (!$user) {
        exit(json_encode(array('status' => '1', 'msg' => 'Người dùng không tồn tại!')));
    }

    $guitoi = $user['email'];
    $subject = 'TOKEN MOMO';
    $bcc = "SIÊU THỊ CODE";
    $hoten = 'Client';
    $token = $row['setupKeyDecrypt'];
    $noi_dung = '<h3>Có ai đó vừa yêu cầu gửi token momo bằng Email này, nếu là bạn thì token bên dưới dùng để chạy api</h3>
        <table>
        <tbody>
        <tr>
        <td style="font-size:20px;">OTP:</td>
        <td><b style="color:blue;font-size:30px;">' . $token . '</b></td>
        </tr>
        </tbody>
        </table>';
    Locdz_Email($guitoi, $hoten, $subject, $noi_dung, $bcc);
    exit(json_encode(array('status' => '2', 'msg' => 'Đã gửi token đến mail của bạn!')));
}
