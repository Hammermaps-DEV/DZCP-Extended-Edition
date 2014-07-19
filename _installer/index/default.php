<?php
if (!defined('IN_DZCP'))
    exit();

setcookie('agb',false);

if(!is_php('5.3.0'))
    $index = writemsg(php_version_error,true);
else
    $index = show("welcome",array("lizenz" => convert::ToHTML(file_get_contents(basePath.'/_installer/system/lizenz.txt')))); //Willkommen & AGB