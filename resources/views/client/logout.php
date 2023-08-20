<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
setcookie('token', null, -1, '/');
session_destroy();
redirect(BASE_URL('client/login'));
?>

