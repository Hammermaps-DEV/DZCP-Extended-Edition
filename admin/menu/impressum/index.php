<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_impressum_head;
$get = db("SELECT i_domain,i_autor FROM ".dba::get('settings'),false,true);
$show = show($dir."/form_impressum", array(
        "domain" => string::decode($get['i_domain']),
        "bbcode" => bbcode::parse_html("seitenautor"),
        "postautor" => string::decode($get['i_autor'])));

$show = show($dir."/imp", array("what" => "impressum", "value" => _button_value_edit, "show" => $show));
if(isset($_GET['do']) && $_GET['do'] == "update")
{
    db("UPDATE ".dba::get('settings')." SET `i_autor` = '".string::encode($_POST['seitenautor'])."', `i_domain` = '".string::encode($_POST['domain'])."' WHERE id = 1");
    $show = info(_config_set, "?admin=impressum");
}