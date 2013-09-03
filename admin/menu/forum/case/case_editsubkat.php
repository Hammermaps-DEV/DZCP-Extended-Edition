<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();


$qry = db("SELECT * FROM ".dba::get('f_skats')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
while($get = _fetch($qry)) //--> Start while subkat sort
{
	$pos = db("SELECT * FROM ".dba::get('f_skats')." WHERE sid = ".$get['sid']."
                       ORDER BY pos");
	while($getpos = _fetch($pos))
	{
		if($get['kattopic'] != $getpos['kattopic'])
		{
			$positions .= show(_select_field, array("value" => $getpos['pos']+1,
			                                        "what" => _nach.' '.string::decode($getpos['kattopic'])));
		}
	}

	$show = show($dir."/skatform", array("head" => _config_forum_edit_skat,
	                                   "fkat" => _config_forum_skatname,
	                                   "fstopic" => _config_forum_stopic,
	                                   "skat" => string::decode($get['kattopic']),
	                                   "what" => "editsubkatsave",
	                                   "stopic" => string::decode($get['subtopic']),
	                                   "id" => $_GET['id'],
	                                   "sid" => $get['sid'],
	                                   "nothing" => _nothing,
	                                   "tposition" => _position,
	                                   "position" => $positions,
	                                   "value" => _button_value_edit));
} //--> End while subkat sort