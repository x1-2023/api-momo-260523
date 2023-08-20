<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if (isset($_POST['id'])) {
    $getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `token`='" . xss($_POST['token']) . "' AND `level`='1'");
    if(!$getUser)
    {
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'Vui lòng đăng nhập'
        ]);
        die($data);
    }
    if($getUser['level']!=1){
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'Không thể thực hiện'
        ]);
        die($data);
    }
    $id = xss($_POST['id']);
    $row = $NNL->get_row("SELECT * FROM `notifications` WHERE `id` = '$id' ");
    if (!$row) {
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'Thông báo không tồn tại trong hệ thống'
        ]);
        die($data);
    }
    $isRemove = $NNL->remove("notifications", " `id` = '$id' ");
    if ($isRemove) {
        $NNL->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => gettime(),
            'action'        => 'Xóa thông báo khỏi hệ thống'
         ]);
        $data = json_encode([
            'status'    => 'success',
            'msg'       => 'Xóa thông thành công'
        ]);
        die($data);
    }
} else {
    $data = json_encode([
        'status'    => 'error',
        'msg'       => 'Dữ liệu không hợp lệ'
    ]);
    die($data);
}