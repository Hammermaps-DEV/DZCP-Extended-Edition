<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

db("UPDATE ".dba::get('settings')."
    SET `k_inhaber`    = '".string::encode($_POST['inhaber'])."',
        `k_nr`         = '".string::encode($_POST['kontonr'])."',
        `k_waehrung`   = '".string::encode($_POST['waehrung'])."',
        `k_bank`       = '".string::encode($_POST['bank'])."',
        `k_blz`        = '".string::encode($_POST['blz'])."',
        `k_vwz`        = '".string::encode($_POST['vwz'])."',
        `iban`         = '".string::encode($_POST['iban'])."',
        `bic`          = '".string::encode($_POST['bic'])."'
    WHERE id = 1");
$show = info(_config_set, "?admin=konto");