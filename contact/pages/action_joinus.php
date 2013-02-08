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
    $joinus = show($dir."/joinus", array("age" => _age,
            "years" => _years));

    $index = show($dir."/contact", array("head" => _site_joinus,
            "nachricht" => _contact_joinus,
            "nick" => _nick,
            "value" => _button_value_send,
            "joinus" => $joinus,
            "what" => "joinus",
            "security" => _register_confirm,
            "why" => _contact_joinus_why,
            "pflicht" => _contact_pflichtfeld,
            "email" => _email,
            "icq" => _icq));
}
?>