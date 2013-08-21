<?php
if (!defined('IN_DZCP'))
    exit();

if(isset($_COOKIE['agb']) && $_COOKIE['agb'] == true)
    $index = show("installtype"); //Auswahl: Update oder Neuinstallation
else if(isset($_POST['agb_checkbox']))
{
    $index = show("installtype"); //Auswahl: Update oder Neuinstallation
    setcookie('agb',true);
}
else
    $index = show("/msg/agb_error"); //AGB nicht akzeptiert!