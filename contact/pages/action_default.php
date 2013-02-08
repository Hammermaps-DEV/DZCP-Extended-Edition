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
    $index = show($dir."/contact", array("head" => _site_contact,
            "nachricht" => _contact_nachricht,
            "nick" => _nick,
            "what" => "contact",
            "security" => _register_confirm,
            "joinus" => "",
            "value" => _button_value_send,
            "why" => "",
            "pflicht" => _contact_pflichtfeld,
            "email" => _email,
            "icq" => _icq));
}
?>