<?php

if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

if (isset($_COOKIE["token"])) {
    $getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `token` = '".check_string($_COOKIE['token'])."' ");
    if (!$getUser) {
        header("location: ".BASE_URL('client/logout'));
        exit();
    }
    $_SESSION['login'] = $getUser['token'];
}
if (!isset($_SESSION['login'])) {
    $my_username = false;
    $my_level = NULL;
} else {
    $getUser = $NNL->get_row(" SELECT * FROM `users` WHERE `token` = '".check_string($_SESSION['login'])."'  ");
    // chuyển hướng đăng nhập khi thông tin login không tồn tại
    if (!$getUser) {
        redirect(BASE_URL('client/login'));
    }
    $my_username =True;
    $my_level = $getUser['level'];
    // chuyển hướng khi bị khoá tài khoản
    if ($getUser['banned'] != 0) {
        // redirect(BASE_URL('common/banned'));
    }
    // khoá tài khoản trường hợp âm tiền, tránh bug
    if ($getUser['money'] < 0) {
        $NNL->update("users", [
            'banned' => 1
        ], " `id` = '".$getUser['id']."' ");
        // redirect(BASE_URL('common/banned'));
    }
     /* cập nhật thời gian online */
     $NNL->update("users", [
        'time_session'  => time()
    ], " `id` = '".$getUser['id']."' ");
}
function CheckLogin()
{
    global $my_username;
    if($my_username != True)
    {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "'.BASE_URL('client/login').'" }, 0);</script>');
    }
}
function CheckAdmin()
{
    global $my_level;
    if($my_level != '1')
    {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "'.BASE_URL('').'" }, 0);</script>');
    }
}
