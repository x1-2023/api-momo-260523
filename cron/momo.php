<?php
define("IN_SITE", true);
require_once("../core/DB.php");
require_once("../core/helpers.php");
$data = array(
    "type" => "history",
    "token"=>$NNL->site('token_momo')
);
$result = curl_post($data,"" . BASE_URL('') . "api/historymomo");
$result = json_decode($result, true);
foreach ($result['momoMsg']['tranList'] as $data) {
    $partnerId      = $data['partnerId'];               // SỐ ĐIỆN THOẠI CHUYỂN
    $comment        = $data['comment'];                 // NỘI DUNG CHUYỂN TIỀN
    $tranId         = $data['tranId'];                  // MÃ GIAO DỊCH
    $partnerName    = $data['partnerName'];             // TÊN CHỦ VÍ
    $amount         = $data['amount'];                  // SỐ TIỀN CHUYỂN
    $user_id        = parse_order_id($comment, $NNL->site('noidungnap_momo'));          // TÁCH NỘI DUNG CHUYỂN TIỀN
    // XỬ LÝ AUTO
    if ($getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `id` = '$user_id' ")) {
        if ($NNL->num_rows(" SELECT * FROM `invoices` WHERE `trans_id` = '$tranId' ") == 0) {
            $insertSv2 = $NNL->insert("invoices", array(
                'trans_id'               => $tranId,
                'payment_method'    => "MOMO",
                'user_id'           => $getUser['id'],
                'description'       => $comment,
                'amount'            => $amount,
                'status'            => 1,
                'create_time'       => time()
            ));
            if ($insertSv2) {
                $isCong = PlusCredits($getUser['id'], $amount, "Nạp tiền tự động qua web MOMO (#$tranId - $comment - $amount)");
                if ($isCong) {
                    echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                }
            }
        }
    }
}

