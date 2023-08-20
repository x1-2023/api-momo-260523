<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
require_once('../../core/class/class.smtp.php');
require_once('../../core/class/PHPMailerAutoload.php');
require_once('../../core/class/class.phpmailer.php');

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
        exit(json_encode(array('status' => '1', 'msg' => 'Tài khoản momo không tồn tại!')));
    }
    $user = $NNL->get_row(" SELECT * FROM `users` WHERE `username` = '" . $row['user_id'] . "' ");
    if (!$user) {
        exit(json_encode(array('status' => '1', 'msg' => 'Người dùng không tồn tại!')));
    }

    $guitoi = $user['email'];
    $subject = 'TOKEN MOMO';
    $bcc = "API SYSTEM";
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
if (isset($_POST['id']) && $_POST['atc'] == 'DELETEMOMO') {
    if (empty($_POST['token'])) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    $id = xss($_POST['id']);
    if (empty($id)) {
        exit(json_encode(array('status' => '1', 'msg' => 'Không được!')));
    }
    $tool = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `id` = '$id' AND `user_id`='" . $getUser['username'] . "' ");
    if (!$tool) {
        exit(json_encode(array('status' => '1', 'msg' => 'Định hack à không dễ vậy đâu!')));
    }
    $NNL->remove("account_momo", "`id`='" . $id . "'");
    exit(json_encode(array('status' => '2', 'msg' => 'Đã xóa tài khoản thành công!')));
}
