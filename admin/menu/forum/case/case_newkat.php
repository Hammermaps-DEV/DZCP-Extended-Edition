<?php
/**
 * <DZCP-Extended Edition>
 *
 * @package : DZCP-Extended Edition
 * @author : DZCP Developer Team || Hammermaps.de Developer Team
 * @link
 */

if (_adminMenu != 'true') exit();

$qry = db("SELECT * FROM " . dba::get('f_kats') . " ORDER BY kid");
while ($get = _fetch($qry))
{
    $positions .= show(_select_field, array("value" => $get['kid'] + 1,
            								"what" => _nach . ' ' . string::decode($get['name']),
            								"sel" => ""));
}

$show = show($dir . "/forum/forum_kat_form", array("fkat" => _config_katname,
			       								  "head" => _config_forum_kat_head,
			        							  "fkid" => _position,
												  "fart" => _kind,
												  "what"=>"addkat",
												  "icon"=>_config_forum_icon,
												  "id" => "",
												  "sel" => "",
												  "nothing" =>"",
												  "icon_edit"=>"",
												  "positions" => $positions,
												  "public" => _config_forum_public,
												  "intern" => _config_forum_intern,
												  "value" => _button_value_add,
												  "kat" => ""));
