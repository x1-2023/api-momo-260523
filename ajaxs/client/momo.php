<?php
define("IN_SITE", true);
require_once "../../core/DB.php";
require_once "../../core/helpers.php";
require_once "../../core/class/Momo.php";
$Momo = new Momo;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'GETOTP') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        $phone = xss($_POST['sdt']);
        $pass = xss($_POST['pass']);
        if (empty($phone)) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Vui lòng điền số điện thoại',
            ]));
        }
        if (empty($pass)) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Vui lòng điền mật khẩu',
            ]));
        }
        if ($getUser['time_momo'] < time()) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Gói API của bạn đã hết hạn sử dụng, vui lòng nâng cấp để tiếp tục sử dụng',
            ]));
        }
        $checkLimit = $NNL->num_rows(" SELECT * FROM `account_momo` WHERE `user_id`='" . $getUser['username'] . "'");
        if ($checkLimit > $NNL->site('limit_api_momo')) {
            exit(json_encode(array('status' => '1', 'msg' => 'Quý dị chỉ được thêm tối đa ' . $NNL->site('limit_api_momo') . ' tài khoản momo')));
        }

        $checkphone = json_decode($Momo->namemomo($phone));
        if ($checkphone->error == 0) {
            die(json_encode([
                'status' => '1',
                'msg' => '' . $checkphone->msg . '',
            ]));
        }

        $getDevice = $NNL->get_row(" SELECT * FROM `device` ORDER BY RAND() LIMIT 1 ");
        if (!$NNL->get_row("SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' ")) {
            $NNL->insert("account_momo", [
                'phone' => $phone,
                'user_id' => $getUser['username'],
                'imei' => $Momo->generateImei(),
                'SECUREID' => $Momo->get_SECUREID(),
                'rkey' => $Momo->generateRandom(20),
                'AAID' => $Momo->generateImei(),
                'TOKEN' => $Momo->get_TOKEN(),
                'device' => $getDevice["device"],
                'hardware' => $getDevice["hardware"],
                'facture' => $getDevice['facture'],
                'status' => 'pending',
                'MODELID' => $getDevice['MODELID'],
            ]);
        }
        $Momo->config = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' LIMIT 1  ");
        $Momo->CHECK_USER_BE_MSG();
        $result = $Momo->SEND_OTP_MSG();
        if ($result["errorCode"] == '0') {
            die(json_encode([
                'status' => '2',
                'msg' => '' . $result["errorDesc"] . '!',
            ]));
        } else {
            die(json_encode([
                'status' => '1',
                'msg' => '' . $result["errorDesc"] . '!',
            ]));
        }
       
    }
    //đăng nhập momo
    if (isset($_POST['action']) && $_POST['action'] == 'CHECKOTP') {
        if (empty($_POST['sdt'])) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Vui lòng điền số điện thoại',
            ]));
        }
        if (empty($_POST['pass'])) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Vui lòng điền mật khẩu',
            ]));
        }
        if (empty($_POST['otp'])) {
            die(json_encode([
                'status' => '1',
                'msg' => 'Vui lòng điền OTP',
            ]));
        }
        $phone = xss($_POST['sdt']);
        $pass = xss($_POST['pass']);
        $code = xss($_POST['otp']);
        $Momo->config = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' LIMIT 1  ");
        $Momo->config['ohash'] = hash('sha256', $Momo->config["phone"] . $Momo->config["rkey"] . $code);
        $NNL->update("account_momo", [
            'ohash' => $Momo->config['ohash'],
        ], " `phone` = '" . $phone . "' ");
        $result = $Momo->REG_DEVICE_MSG();
        
        $setupKeyDecrypt = $Momo->get_setupKey($result["extra"]["setupKey"]);
        $NNL->update("account_momo", [
            'setupKey' => $result["extra"]["setupKey"],
            'status' => 'success',
            'setupKeyDecrypt' => $setupKeyDecrypt,
        ], " `phone` = '" . $phone . "' ");
        $Momo->config = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' LIMIT 1  ");
        $Momo->config["password"] = $pass;
        $result = $Momo->USER_LOGIN_MSG();
       
        if ($result["errorCode"] == '0') {

            $extra = $result["extra"];
            $BankVerify = ($result['momoMsg']['bankVerifyPersonalid'] == 'null') ? '1' : '2';
            $partnerCode = $result['momoMsg']['bankCode'] ?: '';
            $NNL->update("account_momo", [
                'password' => encryptData((string)$Momo->config["password"]),
                'authorization' => $extra["AUTH_TOKEN"],
                'try' => '0',
                'BankVerify' => $BankVerify,
                'agent_id' => $result["momoMsg"]["agentId"],
                'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                'Name' => $extra["FULL_NAME"],
                'BALANCE' => $extra["BALANCE"],
                'refreshToken' => $extra["REFRESH_TOKEN"],
                'sessionkey' => $extra["SESSION_KEY"],
                'partnerCode' => $partnerCode,
                'errorDesc' => $result["errorCode"],
                'status' => 'success',
                'errorDesc' => 'Thành Công',
                'TimeLogin' => time(),
            ], " `phone` = '" . $phone . "' ");
            die(json_encode([
                'status' => '2',
                'msg' => 'Xác nhận otp thành công!',
            ]));
        } else {
            die(json_encode([
                'status' => '1',
                'msg' => 'Xác nhận otp thất bại!',
            ]));
        }
    }
    //get name
    if (isset($_POST['action']) && $_POST['action'] == 'GETNAME') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        $phone = xss($_POST['phone']);
        $checkphone = json_decode($Momo->namemomo($phone));
        if ($checkphone->error == 0) {
            exit(json_encode(array('status' => '1', 'msg' => '' . $checkphone->msg . '')));
        } else {
            exit(json_encode(array('status' => '2', 'msg' => '' . $checkphone->msg . '')));
        }
    }
    //anti trộm
    if (isset($_POST['action']) && $_POST['action'] == 'ANTI') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        $phone = xss($_POST['phone']);
        $status = xss($_POST['status']);
        $ip = xss($_POST['ip']);
        if (empty($phone)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số điện thoại')));
        }
        if (empty($status)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập trạng thái')));
        }
        if (empty($ip)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập IP')));
        }

        $isUpdate = $NNL->update("account_momo", [
            'ip_white' => $ip,
            'status_ip_white' => $status,
        ], " `phone` = '" . $phone . "' ");
        if ($isUpdate) {
            exit(json_encode(array('status' => '2', 'msg' => 'Thay đổi thành công')));
        } else {
            exit(json_encode(array('status' => '1', 'msg' => 'Đã xảy ra lỗi')));
        }
    }
    //get name bank
    if (isset($_POST['action']) && $_POST['action'] == 'getNameBank') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        $bankcode = xss($_POST['bankcode']);
        $phone = xss($_POST['phone']);
        $account_number = xss($_POST['account_number']);
        if (empty($bankcode)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng chọn ngân hàng')));
        }
        if (empty($account_number)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số tài khoản')));
        }
        if (empty($phone)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số điện thoại')));
        }
        $checkUser = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' AND `user_id`='" . $getUser['username'] . "' LIMIT 1  ");
        if (!$checkUser) {
            exit(json_encode(array('status' => '1', 'msg' => 'Không tồn tại momo hoặc không phải của bạn')));
        }
        $token = $checkUser['setupKeyDecrypt'];
        $result = getName_bank($token, $bankcode, $account_number);
        exit(json_encode(array('status' => 2, 'msg' => $result)));
    }
    //send bank
    if (isset($_POST['action']) && $_POST['action'] == 'sendBank') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        $phone = xss($_POST['phone']);
        $money = xss($_POST['money']);
        $pass = xss($_POST['pass']);
        $bankcode = xss($_POST['bankcode']);
        $account_number = xss($_POST['account_number']);
        $content = xss($_POST['content']);
        if (empty($bankcode)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng chọn ngân hàng')));
        }
        if (empty($account_number)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số tài khoản')));
        }
        if (empty($money)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số tiền cần rút về bank')));
        }
        if ((int)$money < 10000) {
            exit(json_encode(array('status' => '1', 'msg' => 'Bạn có thể rút tối thiểu 10.000đ')));
        }
        if (empty($pass)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập mật khẩu momo')));
        }
        if (empty($content)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập nội dung')));
        }
        $ip = myip();
        $checkUser = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $phone . "' AND `user_id`='" . $getUser['username'] . "' LIMIT 1  ");
        if ($checkUser) {
            if ($checkUser['status_ip_white'] == 1) {
                if ($ip != $checkUser['ip_white']) {
                    exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
                }
            } else {
                exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
            }
            if ($pass != decodecryptData($checkUser['password'])) {
                exit(json_encode(array('status' => 1, 'msg' => 'Thông tin không chính xác')));
            }
            $Momo->config = $checkUser;
            $Momo->config['password'] = decodecryptData($checkUser['password']);
            if ($Momo->config['TimeLogin'] < time() - 1800) {
                $result1 = $Momo->GENERATE_TOKEN_AUTH_MSG();
                $extra = $result1["extra"];
                $authen_token = $result1["AUTH_TOKEN"];
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
                        'sessionkey' => $extra["SESSION_KEY"],
                        'errorDesc' => $result1["errorCode"],
                        'TimeLogin'  => time()
                    ], " `phone` = '" . $Momo->config['phone'] . "' ");
                }
            }
            $result = $Momo->SendMoneyBank($bankcode, $account_number, $money, $content);
            $data_send = $result['full'];
            $NNL->insert("send_bank", [
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
            exit(json_encode(array('status' => $result['status'], 'msg' => $result['message'])));
        }
    }
    //chuyển tiền
    if (isset($_POST['action']) && $_POST['action'] == 'SENDMONEY') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $NNL->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
            die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
        }
        if ($getUser['time_momo'] < time()) {
            exit(json_encode(array('status' => '1', 'msg' => 'Gói API của bạn đã hết hạn sử dụng, vui lòng nâng cấp để tiếp tục sử dụng')));
        }
        $phone = xss($_POST['phone']);
        $pass = xss($_POST['pass']);
        $from = xss($_POST['from']);
        $money = xss($_POST['money']);
        $content = xss($_POST['content']);

        if (empty($phone)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số điện thoại cần chuyển')));
        }
        if (empty($money)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập số tiền cần chuyển')));
        }
        if ($money < 100) {
            exit(json_encode(array('status' => '1', 'msg' => 'Số tiền chuyển phải lớn hơn 100đ')));
        }
        if (empty($pass)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập mật khẩu momo')));
        }
        if (empty($content)) {
            exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng nhập nội dung')));
        }
        if ($money < 100) {
            exit(json_encode(array('status' => '1', 'msg' => 'Số tiền chuyển phải lớn hơn 100đ')));
        }
        $ip = myip();
        $checkUser = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $from . "' AND `user_id`='" . $getUser['username'] . "' LIMIT 1  ");
        if ($checkUser) {
            if ($checkUser['status_ip_white'] == 1) {
                if ($ip != $checkUser['ip_white']) {
                    exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
                }
            } else {
                exit(json_encode(array('status' => 'false', 'msg' => 'Bạn không có quyền thực hiện chuyển tiền')));
            }
            if ($pass != decodecryptData($checkUser['password'])) {
                exit(json_encode(array('status' => 1, 'msg' => 'Thông tin không chính xác')));
            }
        }
        $Momo->config = $NNL->get_row(" SELECT * FROM `account_momo` WHERE `phone` = '" . $from . "' AND `user_id`='" . $getUser['username'] . "' LIMIT 1  ");
        $Momo->config['password'] = decodecryptData($checkUser['password']);
        if ($Momo->config['TimeLogin'] < time() - 1800) {
            $result1 = $Momo->GENERATE_TOKEN_AUTH_MSG();
            $extra = $result1["extra"];
            $authen_token = $result1["AUTH_TOKEN"];
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
                    'TimeLogin' => time(),
                ], " `phone` = '" . $Momo->config['phone'] . "' ");
            } else {
                $NNL->update("account_momo", [
                    'authorization' => $extra["AUTH_TOKEN"],
                    'RSA_PUBLIC_KEY' => $extra["REQUEST_ENCRYPT_KEY"],
                    'sessionkey' => $extra["SESSION_KEY"],
                    'errorDesc' => $result1["errorCode"],
                    'TimeLogin' => time(),
                ], " `phone` = '" . $Momo->config['phone'] . "' ");
            }
        }
        $result = $Momo->SendMoney($phone, $money, $content);
        $data_send = $result['full'];
        $NNL->insert("send", [
            'momo_id' => isset($result['tranDList']['ID']) ? $result['tranDList']['ID'] : "default",
            'tranId' => isset($result['tranDList']['tranId']) ? $result['tranDList']['tranId'] : "default",
            'partnerId' => isset($result['tranDList']['partnerId']) ? $result['tranDList']['partnerId'] : "default",
            'partnerName' => isset($result['tranDList']['partnerName']) ? $result['tranDList']['partnerName'] : "default",
            'amount' => isset($result['tranDList']['amount']) ? $result['tranDList']['amount'] : "default",
            'comment' => isset($result['tranDList']['comment']) ? $result['tranDList']['comment'] : "default",
            'time' => time(),
            'user_id' => $getUser["username"],
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $data_send,
            'balance' => $result['tranDList']['balance'],
            'ownerNumber' => isset($result['tranDList']['ownerNumber']) ? $result['tranDList']['ownerNumber'] : "default",
            'ownerName' => isset($result['tranDList']['ownerName']) ? $result['tranDList']['ownerName'] : "default",
        ]);
        exit(json_encode(array('status' => $result['status'], 'msg' => $result['message'])));
    }
}
