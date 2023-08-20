<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if (isset($_POST['id'])) {
    $getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `token`='" . check_string($_POST['token']) . "' AND `level`='1'");
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
    $row = $NNL->get_row("SELECT * FROM `users` WHERE `id` = '$id' ");
    if (!$row) {
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'User không tồn tại trong hệ thống'
        ]);
        die($data);
    }
    $isRemove = $NNL->remove("users", " `id` = '$id' ");
    if ($isRemove) {
        $NNL->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => gettime(),
            'action'        => 'Xóa thành viên ('.$row['username'].') Số dư còn: '.format_cash($row['money']).', Tổng nạp: '.format_cash($row['total_money']).''
         ]);
        $data = json_encode([
            'status'    => 'success',
            'msg'       => 'Xóa thành viên thành công'
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