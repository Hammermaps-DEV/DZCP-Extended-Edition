<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry  = db("SELECT * FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$get = _fetch($qry);

$qryk = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name");
while($getk = _fetch($qryk))
{
	$kats .= show(_select_field, array("value" => $getk['id'], "what" => string::decode($getk['name']), "sel" => ($getk['id'] == $get['kat'] ? 'selected="selected"' : '')));
}

$selr_dc = ($get['comments'] ? 'selected="selected"' : '');
$show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head_edit,
                                    "ddownload" => string::decode($get['download']),
                                    "durl" => string::decode($get['url']),
                                    "file" => $dl,
                                    "selr_dc" => $selr_dc,
                                    "lokal" => _downloads_lokal,
                                    "exist" => _downloads_exist,
                                    "nothing" => _nothing,
                                    "nofile" => _downloads_nofile,
                                    "oder" => _or,
                                    "dbeschreibung" => string::decode($get['beschreibung']),
                                    "kat" => _downloads_kat,
                                    "what" => _button_value_edit,
                                    "do" => "editdl&amp;id=".$_GET['id']."",
                                    "kats" => $kats,
                                    "url" => _downloads_url,
                                    "beschreibung" => _beschreibung,
                                    "download" => _downloads_name));

