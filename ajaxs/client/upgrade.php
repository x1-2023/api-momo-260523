<?php

define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['token'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (empty($_POST['type_id'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng chọn gói nâng cấp']));
    }
    if (empty($_POST['month'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng chọn thời gian gia hạn']));
    }
    if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '".xss($_POST['token'])."' AND `banned`='0' ")) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$service = $NNL->get_row("SELECT * FROM `service_api` WHERE `id` = '".xss($_POST['type_id'])."'")) {
        die(json_encode(['status' => 'error', 'msg' => 'Gói nâng cấp không tồn tại']));
    }
    if (xss($_POST['month']) <= 0) {
        Banned($getUser['id'], 'Gian lận khi sửa thông tin');
        die(json_encode(['status' => 'error', 'msg' => 'Bạn đã bị khoá tài khoản vì sửa thông tin']));
    }
    $time = time();
    $money_api = $service['money'];
    $month = xss($_POST['month']) * 30;
    if ($getUser['time_momo'] < $time) {
        $timeto = $time + 86400 * $month;
        $total_payment = $money_api * xss($_POST['month']);
        // exit(json_encode(array('status' => '1', 'msg' => 'hết hạn!')));
    } else {
        $timeto = $getUser['time_momo'] + 86400 *  $month;
        $total_payment = $money_api * xss($_POST['month']);
        // exit(json_encode(array('status' => '2', 'msg' => 'còn hạn!')));
    }
    if (getRowRealtime("users", $getUser['id'], "money") < $total_payment) {
        die(json_encode(['status' => 'error', 'msg' => 'Số dư không đủ ' . format_cash($total_payment) . 'đ, vui lòng nạp thêm']));
    }
    $isBuy = RemoveCredits($getUser['id'], $total_payment, "Nâng cấp gói api momo" . $month." ngày");
    if ($isBuy) {
        if (getRowRealtime("users", $getUser['id'], "money") < 0) {
            Banned($getUser['id'], 'Gian lận khi nâng cấp gói api momo');
            die(json_encode(['status' => 'error', 'msg' => 'Bạn đã bị khoá tài khoản vì gian lận']));
        }
        //insert logs
        $NNL->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => gettime(),
            'action'        => 'Nâng cấp gói api momo ('.$month.') ngày'
         ]);
         //update time api
         $isUpdate = $NNL->update("users", [
            'ip' => myip(),
            'time_momo' =>  $timeto,
            'time_session' => time(),
            'device' => $_SERVER['HTTP_USER_AGENT']
        ], " `id` = '".$getUser['id']."' ");
        die(json_encode(['status' => 'success', 'msg' => 'Nâng cấp gói api thành công']));
    }
    else {
        die(json_encode(['status' => 'error', 'msg' => 'Nâng cấp thất bại, vui lòng thử lại']));
    }
}

