<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();


$qry = db("SELECT * FROM ".dba::get('f_skats')." WHERE sid = " . convert::ToInt($_GET['id']) .
	" ORDER BY pos");
while($get = _fetch($qry))
{
	$positions .= show(_select_field, array("value" => $get['pos']+1,
	                                        "what" => _nach.' '.string::decode($get['kattopic']),
	                                        "sel" => ""));
}
$show = show($dir."/skatform", array("head" => _config_forum_add_skat,
                                     "fkat" => _config_forum_skatname,
                                     "fstopic" => _config_forum_stopic,
                                     "skat" => "",
                                     "what" => "addsubkat",
                                     "stopic" => "",
                                     "id" => convert::ToInt($_GET['id']),
                                     "nothing" => "",
                                     "tposition" => _position,
                                     "position" => $positions,
                                     "value" => _button_value_add));