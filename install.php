<?php
define("IN_SITE", true);
require_once(__DIR__.'/core/DB.php');
require_once(__DIR__.'/core/helpers.php');

    function insert_options($key, $value)
    {
        global $NNL;
        if (!$NNL->get_row("SELECT * FROM `settings` WHERE `name` = '$key' ")) {

            $NNL->query("INSERT INTO `settings` (`name`, `value`) VALUES ('$key', '$value')");
        }
    }
   insert_options('link_facebook', '');
   insert_options('notification', '');

  
    
    die('Success!');