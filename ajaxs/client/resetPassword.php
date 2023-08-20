<?php

define("IN_SITE", true);
require_once "../../core/DB.php";
require_once "../../core/helpers.php";
require_once '../../core/class/class.smtp.php';
require_once '../../core/class/PHPMailerAutoload.php';
require_once '../../core/class/class.phpmailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'forgotPassword') {
        if (empty($_POST['email'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập email']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `email` = '" . xss($_POST['email']) . "'")) {
            die(json_encode(['status' => 'error', 'msg' => 'Địa chỉ email không tồn tại trong hệ thống']));
        }
        $otp = random('0123456789', '6');
        $NNL->update("users", array(
            'otp' => $otp,
        ), " `id` = '" . $getUser['id'] . "' ");
        $guitoi = $getUser['email'];
        $subject = 'KHÔI PHỤC MẬT KHẨU';
        $bcc = "SIÊU THỊ CODE";
        $hoten = 'Client';
        $noi_dung = '<h3>Có ai đó vừa yêu cầu gửi otp khôi phục mật khẩu bằng Email này, nếu là bạn thì otp bên dưới dùng để xác thực khôi phục</h3>
        <table>
        <tbody>
        <tr>
        <td style="font-size:20px;">OTP:</td>
        <td><b style="color:blue;font-size:30px;">' . $otp . '</b></td>
        </tr>
        </tbody>
        </table>';
        Locdz_Email($guitoi, $hoten, $subject, $noi_dung, $bcc);
        die(json_encode(['status' => 'success', 'msg' => 'Chúng tôi đã gửi 1 mã OTP khôi phục đến email của bạn']));
    }
    if (isset($_POST['action']) && $_POST['action'] == 'resetPassword') {
        $otp = xss($_POST['otp']);
        $repassword = xss($_POST['repassword']);
        $password = xss($_POST['password']);
        if (empty($otp)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập otp']));
        }
        if (empty($password)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu mới']));
        }
        if (empty($repassword)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng xác minh lại mật khẩu']));
        }
        $row = $NNL->get_row(" SELECT * FROM `users` WHERE `otp` = '$otp' ");
        if (!$row) {
            die(json_encode(['status' => 'error', 'msg' => 'OTP không tồn tại trong hệ thống']));
        }
        if ($password != $repassword) {
            die(json_encode(['status' => 'error', 'msg' => 'Nhập lại mật khẩu không đúng']));
        }
        if (strlen($password) < 6) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu có ích nhất 6 ký tự']));
        }
        $NNL->update("users", [
            'otp' => null,
            'password' => sha1($password),
        ], " `id` = '" . $row['id'] . "' ");
        $NNL->insert("logs", [
            'user_id' => $row['id'],
            'ip' => myip(),
            'device' => $_SERVER['HTTP_USER_AGENT'],
            'create_date' => gettime(),
            'action' => 'Khôi phục mật khẩu thành công',
        ]);
        die(json_encode(['status' => 'success', 'msg' => 'Đã khôi phục mật khẩu thành công']));
    }
} else {
    die('The Request Not Found');
}
