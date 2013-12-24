<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$where = $where.': '._config_impressum_head;
if(isset($_GET['do']) && $_GET['do'] == "update")
{
    settings::set('i_autor',string::encode($_POST['seitenautor']));
    settings::set('i_domain',string::encode($_POST['domain']));
    $show = info(_config_set, "?index=admin&amp;admin=impressum");
}

if(empty($show))
{
    $get = settings(array('i_domain','i_autor'));
    $show = show($dir."/form_impressum", array(
            "domain" => string::decode($get['i_domain']),
            "bbcode" => bbcode::parse_html("seitenautor"),
            "postautor" => string::decode($get['i_autor'])));

    $show = show($dir."/imp", array("what" => "impressum", "value" => _button_value_edit, "show" => $show));
}