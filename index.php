<?php
define("IN_SITE", true);
require_once(__DIR__.'/core/DB.php');
require_once(__DIR__.'/core/helpers.php');
$module = !empty($_GET['module']) ? $_GET['module'] : 'client';
$action = !empty($_GET['action']) ? $_GET['action'] : 'home';
$path = "resources/views/$module/$action.php";
if (file_exists($path)) {
    require_once(__DIR__.'/'.$path);
    exit();
} else {
    require_once(__DIR__.'/resources/views/errors/404.php');
    exit();
}
?>
