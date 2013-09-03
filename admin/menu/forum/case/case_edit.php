<?php
/**
 * <DZCP-Extended Edition>
 *
 * @package : DZCP-Extended Edition
 * @author : DZCP Developer Team || Hammermaps.de Developer Team
 * @link
 */

if (_adminMenu != 'true') exit();

$qry = db("SELECT * FROM " . dba::get('f_kats') . " WHERE id = '" . convert::ToInt($_GET['id']) . "'");

while ($get = _fetch($qry))
	 {
	    $pos = db("SELECT * FROM " . dba::get('f_kats') . " ORDER BY kid");

	    while ($getpos = _fetch($pos))
	    	{
	        if ($get['name'] != $getpos['name']) {
	            $positions .= show(_select_field, array("value" => $getpos['kid'] + 1,
	                   								    "what" => _nach . ' ' . string::decode($getpos['name'])));
	        }
     }

	$show = show($dir . "/katform_edit", array("fkat" => _config_katname,
            								   "head" => _config_forum_kat_head_edit,
            								   "fkid" => _position,
            								   "fart" => _kind,
								               "id" => convert::ToInt($get['id']),
								               "sel" => ($get['intern'] ? 'selected="selected"' : ''),
								               "nothing" => _nothing,
								               "positions" => $positions,
								               "public" => _config_forum_public,
								               "intern" => _config_forum_intern,
								               "value" => _button_value_edit,
								               "kat" => string::decode($get['name'])));
}