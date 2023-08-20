<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Tài khoản không được để trống']));
    }
    if (empty($_POST['email'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Email không được để trống']));
    }
    if (empty($_POST['password'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Mật khẩu không được để trống']));
    }
    if (empty($_POST['repassword'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Nhập lại mật khẩu không được để trống']));
    }
    $username = xss($_POST['username']);
    $email = xss($_POST['email']);
    $password = xss($_POST['password']);
    $repassword = xss($_POST['repassword']);
    if (check_username($username) != true) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập định dạng tài khoản hợp lệ']));
    }
    if ($password != $repassword) {
        die(json_encode(['status' => 'error', 'msg' => 'Nhập lại mật khẩu không đúng']));
    }
    if (check_email($email) != true) {
        die(json_encode(['status' => 'error', 'msg' => 'Định dạng Email không đúng']));
    }
    if ($NNL->num_rows("SELECT * FROM `users` WHERE `username` = '$username' ") > 0) {
        die(json_encode(['status' => 'error', 'msg' => 'Tên đăng nhập đã tồn tại trong hệ thống']));
    }
    if ($NNL->num_rows("SELECT * FROM `users` WHERE `email` = '$email' ") > 0) {
        die(json_encode(['status' => 'error', 'msg' => 'Địa chỉ email đã tồn tại trong hệ thống']));
    }
    if ($NNL->num_rows("SELECT * FROM `users` WHERE `ip` = '" . myip() . "' ") >= 10) {
        die(json_encode(['status' => 'error', 'msg' => 'IP của bạn đã đạt giới hạn tạo tài khoản cho phép']));
    }
    # Create the 2FA class
    // $google2fa = new Google2FA();
    $token = md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time());
    $isCreate = $NNL->insert("users", [
        'token'         => $token,
        'username'      => $username,
        'email'         => $email,
        'password'      => sha1($password),
        'ip'            => myip(),
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'   => gettime(),
        'update_date'   => time(),
        'time_session'  => time(),
        'time_momo'     => time() + $NNL->site('time_test_api')
    ]);
    if ($isCreate) {
        $NNL->insert("logs", [
            'user_id'       => $NNL->get_row("SELECT * FROM `users` WHERE `token` = '$token' ")['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => gettime(),
            'action'        => 'Đăng ký tài khoản thành công'
         ]);

         $NNL->update("users", [
            'time_session' => time(),
        ], " `id` = '".$NNL->get_row("SELECT * FROM `users` WHERE `token` = '$token' ")['id']."' ");

        setcookie("token", $token, time() + $NNL->site('session_login'), "/");
        $_SESSION['login'] = $token;
        die(json_encode(['status' => 'success', 'msg' => 'Đăng ký thành công']));
    } else {
        die(json_encode(['status' => 'error', 'msg' => 'Tạo tài khoản thất bại, vui lòng thử lại']));
    }
}
