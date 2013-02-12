<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if($_POST['secure'] != $_SESSION['sec_slist'] || empty($_SESSION['sec_slist']))
        $index = error(_error_invalid_regcode,1);
    elseif(empty($_POST['clanname']))
    $index = error(_error_empty_clanname, 1);
    elseif(empty($_POST['ip']))
    $index = error(_error_empty_ip, 1);
    elseif(empty($_POST['port']))
    $index = error(_error_empty_port, 1);
    elseif(empty($_POST['slots']))
    $index = error(_error_empty_slots, 1);
    else {
        $msg = _slist_added_msg;
        $title = _slist_title;
        $send = db("INSERT INTO ".$db['msg']."
                SET `datum`     = '".time()."',
                    `von`       = '0',
                    `an`        = '1',
                    `titel`     = '".up($title)."',
                    `nachricht` = '".up($msg)."'");

        $insert = db("INSERT INTO ".$db['serverliste']."
                  SET `datum`     = '".time()."',
                      `clanname`  = '".up($_POST['clanname'])."',
                      `clanurl`   = '".links($_POST['clanurl'])."',
                      `ip`        = '".up($_POST['ip'])."',
                      `port`      = '".convert::ToInt($_POST['port'])."',
                      `pwd`       = '".up($_POST['pwd'])."',
                      `slots`     = '".convert::ToInt($_POST['slots'])."'");

        $index = info(_error_server_saved, "../serverliste/");
    }
}
?>