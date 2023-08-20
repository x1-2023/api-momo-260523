<?php
define("IN_SITE", true);
require_once("../core/DB.php");
require_once("../core/helpers.php");
require_once("../core/class/Momo.php");
error_reporting(0);
set_time_limit(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
$Momo = new Momo;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['type']) && $_POST['type'] == 'balance') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => 'false', 'msg' => 'Thiếu Token']));
        }
        $getData = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `setupKeyDecrypt` = '" . xss($_POST["token"]) . "' LIMIT 1");
        if ($getData) {
            $myUser = $NNL->get_row(" SELECT * FROM `users` WHERE `username` = '" . $getData["user_id"] . "'");
            if ($myUser['time_momo'] < time()) {
                die(json_encode(['status' => 'false', 'msg' => 'Tài khoản của bạn đã hết hạn sử dụng, vui lòng nâng cấp gói để tiếp tục sử dụng!']));
            } else {
                $Momo->config = $getData;
                $Momo->config['password'] = decodecryptData($getData['password']);

                if ($Momo->config['TimeLogin'] < time() - 1800) {
                    $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
                    $extra = $result["extra"];
                    $authen_token = $extra["AUTH_TOKEN"];
                    if (!isset($authen_token)) {
                        $result_login = $Momo->USER_LOGIN_MSG();
                        $extra_login = $result_login["extra"];
                        $BankVerify = ($result_login['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
                        $partnerCode = $result_login['momoMsg']['bankCode'] ?: '';
                        $NNL->update("account_momo", [
                            'authorization' => $extra_login["AUTH_TOKEN"],
                            'try' => '0',
                            'BankVerify' => $BankVerify,
                            'agent_id' => $result_login["momoMsg"]["agentId"],
                            'RSA_PUBLIC_KEY' => $extra_login["REQUEST_ENCRYPT_KEY"],
                            'refreshToken' => $extra_login["REFRESH_TOKEN"],
                            'sessionkey' => $extra_login["SESSION_KEY"],
                            'partnerCode' => $partnerCode,
                            'errorDesc' => $extra_login["errorCode"],
                            'status' => 'success',
                            'errorDesc' => 'Thành Công',
                            'TimeLogin' => time()
                        ], " `phone` = '" . $Momo->config['phone'] . "' ");
                    } else {
                        $NNL->update("account_momo", [
                            'authorization' => $extra["AUTH_TOKEN"],
                            'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                            'errorDesc' => $result["errorCode"],
                            'TimeLogin'  => time()
                        ], " `phone` = '" . $Momo->config['phone'] . "' ");
                    }
                }
                $from = date("d/m/Y", strtotime("1 days ago"));
                $lichsu = $Momo->TRAN_HIS_LIST($from, date("d/m/Y", time()), 5);
                if (isset($lichsu['momoMsg'])) {
                    $money = isset($lichsu['momoMsg'][0]['postBalance']) ? $lichsu['momoMsg'][0]['postBalance'] : 0;
                    exit(json_encode(array('status' => '200', 'SoDu' => '' . $money . '')));
                } else {
                    exit(json_encode(array('status' => '99', 'SoDu' => '0')));
                }
            }
        } else {
            die(json_encode(['status' => 'false', 'msg' => 'Authorization Token not found']));
        }
    }
    //check name
     if (isset($_POST['type']) && $_POST['type'] == 'checkname') {
        if (empty($_POST['phone'])) {
            die(json_encode(['status' => 'false', 'msg' => 'Thiếu Phone']));
        }
        $phone = xss($_POST['phone']);
        $checkphone = json_decode($Momo->namemomo($phone));
        if ($checkphone->error == 0) {
            die(json_encode([
                'status' => '99',
                'msg' => '' . $checkphone->msg . '',
            ]));
        }
        die(json_encode([
                'status' => '200',
                'msg' => '' . $checkphone->msg . '',
        ]));
    }
    
    //Lấy lsgd
    if (isset($_POST['type']) && $_POST['type'] == 'history') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => 'false', 'msg' => 'Token is valid']));
        }
        $getData = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `setupKeyDecrypt` = '" . xss($_POST["token"]) . "' LIMIT 1");
        if ($getData) {
            $myUser = $NNL->get_row(" SELECT * FROM `users` WHERE `username` = '" . $getData["user_id"] . "'");
            if ($myUser['time_momo'] < time()) {
                die(json_encode(['status' => 'false', 'msg' => 'Tài khoản của bạn đã hết hạn sử dụng, vui lòng nâng cấp gói để tiếp tục sử dụng!']));
            } else {
                $Momo->config = $getData;
                $Momo->config['password'] = decodecryptData($getData['password']);
                if ($Momo->config['TimeLogin'] < time() - 1800) {
                    $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
                    $extra = $result["extra"];
                    $authen_token = $extra["AUTH_TOKEN"];
                    if (!isset($authen_token)) {
                        $result_login = $Momo->USER_LOGIN_MSG();
                        $extra_login = $result_login["extra"];
                        $BankVerify = ($result_login['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
                        $partnerCode = $result_login['momoMsg']['bankCode'] ?: '';
                        $NNL->update("account_momo", [
                            'authorization' => $extra_login["AUTH_TOKEN"],
                            'try' => '0',
                            'BankVerify' => $BankVerify,
                            'agent_id' => $result_login["momoMsg"]["agentId"],
                            'RSA_PUBLIC_KEY' => $extra_login["REQUEST_ENCRYPT_KEY"],
                            'refreshToken' => $extra_login["REFRESH_TOKEN"],
                            'sessionkey' => $extra_login["SESSION_KEY"],
                            'partnerCode' => $partnerCode,
                            'errorDesc' => $extra_login["errorCode"],
                            'status' => 'success',
                            'errorDesc' => 'Thành Công',
                            'TimeLogin' => time()
                        ], " `phone` = '" . $getData['phone'] . "' ");
                    } else {
                        $NNL->update("account_momo", [
                            'authorization' => $extra["AUTH_TOKEN"],
                            'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                            'errorDesc' => $result["errorCode"],
                            'TimeLogin'  => time()
                        ], " `phone` = '" . $Momo->config['phone'] . "' ");
                    }
                }
                $history = $Momo->CheckHistoryV2(1,15);
                print_r($history);
            }
        } else {
            die(json_encode(['status' => 'false', 'msg' => 'Authorization Token not found']));
        }
    }
    if (isset($_POST['type']) && $_POST['type'] == 'transfer') {
        if (isset($_POST["token"]) && isset($_POST["phone"]) && isset($_POST["password"]) && isset($_POST["amount"]) && isset($_POST["comment"])) {
            $token = xss($_POST["token"]);
            $phone = xss($_POST['phone']);
            $password = xss($_POST["password"]);
            $money = xss($_POST["amount"]);
            $content = xss($_POST['comment']);
            $ip = xss($_POST['ip']);
            if (empty($content)) {
                exit();
            }
            //check thông tin
            $row = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `setupKeyDecrypt` = '" . $token . "'");
            if (!$row) {
                exit(json_encode(array('status' => 'false', 'msg' => 'Thông tin api không chính xác')));
            }
            if($password!=decodecryptData($row['password'])){
                    exit(json_encode(array('status' => 'false', 'msg' => 'Thông tin api không chính xác')));
                }
            //check hạn sử dụng
            $getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `username`='" . $row['user_id'] . "'");
            if ($getUser['time_momo'] < time()) {
                exit(json_encode(array('status' => 'false', 'msg' => 'Gói API của bạn đã hết hạn sử dụng, vui lòng nâng cấp để tiếp tục sử dụng')));
            }
            //check trộm
            if ($row['status_ip_white'] == 1) {
                if ($ip != $row['ip_white']) {
                    exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
                }
            }else{
                exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
            }
            //check die token
            $Momo->config = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $row['phone'] . "' AND `user_id`='" . $getUser['username'] . "' LIMIT 1  ");
            $Momo->config['password'] = decodecryptData($row['password']);
            if ($Momo->config['TimeLogin'] < time() - 1800) {
                $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
                $extra = $result["extra"];
                $authen_token = $extra["AUTH_TOKEN"];
                if (!isset($authen_token)) {
                    $result_login = $Momo->USER_LOGIN_MSG();
                    $extra_login = $result_login["extra"];
                    $BankVerify = ($result_login['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
                    $partnerCode = $result_login['momoMsg']['bankCode'] ?: '';
                    $NNL->update("account_momo", [
                        'authorization' => $extra_login["AUTH_TOKEN"],
                        'try' => '0',
                        'BankVerify' => $BankVerify,
                        'agent_id' => $result_login["momoMsg"]["agentId"],
                        'RSA_PUBLIC_KEY' => $extra_login["REQUEST_ENCRYPT_KEY"],
                        'refreshToken' => $extra_login["REFRESH_TOKEN"],
                        'sessionkey' => $extra_login["SESSION_KEY"],
                        'partnerCode' => $partnerCode,
                        'errorDesc' => $extra_login["errorCode"],
                        'status' => 'success',
                        'errorDesc' => 'Thành Công',
                        'TimeLogin' => time()
                    ], " `phone` = '" . $Momo->config['phone'] . "' ");
                } else {
                    $NNL->update("account_momo", [
                        'authorization' => $extra["AUTH_TOKEN"],
                        'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                        'errorDesc' => $result["errorCode"],
                        'TimeLogin'  => time()
                    ], " `phone` = '" . $Momo->config['phone'] . "' ");
                }
            }
            $result = $Momo->SendMoney($phone, $money, $content);
            $data_send = $result['full'];
            $NNL->insert("send", [
                'momo_id'               => isset($result['tranDList']['ID']) ? $result['tranDList']['ID'] : "default",
                'tranId'                 => isset($result['tranDList']['tranId']) ? $result['tranDList']['tranId'] : "default",
                'partnerId'                => isset($result['tranDList']['partnerId']) ? $result['tranDList']['partnerId'] : "default",
                'partnerName'                    => isset($result['tranDList']['partnerName']) ? $result['tranDList']['partnerName'] : "default",
                'amount'             => isset($result['tranDList']['amount']) ? $result['tranDList']['amount'] : "default",
                'comment'           => isset($result['tranDList']['comment']) ? $result['tranDList']['comment'] : "default",
                'time'           => time(),
                'user_id'                => $getUser["username"],
                'status'               => $result['status'],
                'message'           => $result['message'],
                'data'             => $data_send,
                'balance'           => $result['tranDList']['balance'],
                'ownerNumber'             => isset($result['tranDList']['ownerNumber']) ? $result['tranDList']['ownerNumber'] : "default",
                'ownerName'           => isset($result['tranDList']['ownerName']) ? $result['tranDList']['ownerName'] : "default"
            ]);
            exit(json_encode(array(
                'status' => $result['status'], 
                'ID' => $result['tranDList']['ID'], 
                'transId' => $result['tranDList']['tranId'], 
                'amount' => $result['tranDList']['amount'], 
                'balance' => $result['tranDList']['balance'], 
                'comment' => isset($result['tranDList']['comment']) ? $result['tranDList']['comment'] : "default", 
                'partnerId' => $result['tranDList']['partnerId'], 
                'partnerName' => $result['tranDList']['partnerName'], 
                'ownerNumber' => $result['tranDList']['ownerNumber'], 
                'ownerName' => $result['tranDList']['ownerName'], 
                'msg' => $result['message'],
                'full' =>$result['full']
            )));
        }
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getnamebank') {
        if (isset($_POST["token"]) && isset($_POST["bankcode"]) && isset($_POST["account_number"])) {
            $getData = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `setupKeyDecrypt` = '" . xss($_POST["token"]) . "' LIMIT 1");
            if ($getData) {
                $myUser = $NNL->get_row(" SELECT * FROM `users` WHERE `username` = '" . $getData["user_id"] . "'");
                if ($myUser['time_momo'] < time()) {
                    exit(json_encode(array('status' => 'false', 'msg' => 'Gói API của bạn đã hết hạn sử dụng, vui lòng nâng cấp để tiếp tục sử dụng')));
                } else {
                    $Momo->config = $getData;
                    $Momo->config['password'] = decodecryptData($getData['password']);
                    if ($Momo->config['TimeLogin'] < time() - 1800) {
                        $result = $Momo->GENERATE_TOKEN_AUTH_MSG();
                        $extra = $result["extra"];
                        $authen_token = $extra["AUTH_TOKEN"];
                        if (!isset($authen_token)) {
                            $result_login = $Momo->USER_LOGIN_MSG();
                            $extra_login = $result_login["extra"];
                            $BankVerify = ($result_login['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
                            $partnerCode = $result_login['momoMsg']['bankCode'] ?: '';
                            $NNL->update("account_momo", [
                                'authorization' => $extra_login["AUTH_TOKEN"],
                                'try' => '0',
                                'BankVerify' => $BankVerify,
                                'agent_id' => $result_login["momoMsg"]["agentId"],
                                'RSA_PUBLIC_KEY' => $extra_login["REQUEST_ENCRYPT_KEY"],
                                'refreshToken' => $extra_login["REFRESH_TOKEN"],
                                'sessionkey' => $extra_login["SESSION_KEY"],
                                'partnerCode' => $partnerCode,
                                'errorDesc' => $extra_login["errorCode"],
                                'status' => 'success',
                                'errorDesc' => 'Thành Công',
                                'TimeLogin' => time()
                            ], " `phone` = '" . $Momo->config['phone'] . "' ");
                        } else {
                            $NNL->update("account_momo", [
                                'authorization' => $extra["AUTH_TOKEN"],
                                'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                                'errorDesc' => $result["errorCode"],
                                'TimeLogin'  => time()
                            ], " `phone` = '" . $Momo->config['phone'] . "' ");
                        }
                    }
                    $bankcode = xss($_POST['bankcode']);
                    $account_number = xss($_POST['account_number']);
                    $lichsu = $Momo->checkNameBank($bankcode, $account_number);
                    if (isset($lichsu['status']) && $lichsu['status'] == 2) {
                        exit(json_encode(array('status' => '200', 'name' => '' . $lichsu['message'] . '')));
                    } else {
                        exit(json_encode(array('status' => '99', 'name' => 'Không tồn tại người dùng bank')));
                    }
                    print_r($lichsu);
                }
            } else {
                die('{"status":99,"msg":"Access_token không tồn tại!"}');
            }
        }
    }
}
