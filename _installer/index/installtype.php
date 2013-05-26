<?php
if (!defined('IN_DZCP'))
exit();

if(isset($_POST['agb_checkbox']))
{
    $index = show("installtype"); //Auswahl: Update oder Neuinstallation
    $_SESSION['agb'] = true;
}
else
    $index = show("/msg/agb_error"); //AGB nicht akzeptiert!