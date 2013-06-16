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
    $index = show($dir."/add", array("add_head" => _slist_add,
            "clan" => _profil_clan,
            "hp" => _profil_hp,
            "what" => "slist",
            "security" => _register_confirm,
            "serverpasswort" => _server_password,
            "serverip" => _slist_serverip,
            "serverport" => _slist_serverport,
            "value" => _button_value_add,
            "slots" => _slist_slots,
            "serverpassword" => _server_password));
}