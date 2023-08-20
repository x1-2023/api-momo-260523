<?php

define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Username không được để trống'
        ]));
    }
    if (empty($_POST['password'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Mật khẩu không được để trống'
        ]));
    }
    $username = xss($_POST['username']);
    $password = xss($_POST['password']);
    if (check_username($username) != true) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập định dạng tài khoản hợp lệ']));
    }
    $getUser = $NNL->get_row("SELECT * FROM `users` WHERE `username` = '$username' ");
    if (!$getUser) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Thông tin đăng nhập không chính xác'
        ]));
    }
    $Check = $NNL->get_row("SELECT * FROM `users` WHERE `username` = '$username' AND `password`='".sha1($password)."' ");
    if (!$Check) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Thông tin đăng nhập không chính xác'
        ]));
    }
    if ($getUser['banned'] == 1) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Tài khoản của bạn đã bị khoá truy cập'
        ]));
    }
    $NNL->insert("logs", [
        'user_id'       => $getUser['id'],
        'ip'            => myip(),
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'    => gettime(),
        'action'        => 'Đăng nhập thành công vào hệ thống'
     ]);
    $NNL->update("users", [
        'ip' => myip(),
        'time_session' => time(),
        'device' => $_SERVER['HTTP_USER_AGENT']
    ], " `id` = '".$getUser['id']."' ");

    setcookie("token", $getUser['token'], time() + $NNL->site('session_login'), "/");
    $_SESSION['login'] = $getUser['token'];
    die(json_encode([
        'status' => 'success',
        'msg'    => 'Đăng nhập thành công'
    ]));
}
