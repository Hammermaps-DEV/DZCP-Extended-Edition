<?php
if (!defined('IN_DZCP'))
    exit();

if($_COOKIE['agb'] =! true)
    $index = show("/msg/agb_error");
else
{
    $index = show("done");
    $_SESSION['db_install'] = false;

    //Unset Installer Sessions
    unset($_SESSION['setup_step']);


}