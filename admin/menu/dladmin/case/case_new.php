<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name");
while($get = _fetch($qry))
{
	$kats .= show(_select_field, array("value" => $get['id'], "what" => string::decode($get['name']), "sel" => ""));
}

$files = get_files(basePath.'/downloads/files/',false,true); $dl = '';
foreach($files as $file)
{ $dl .= show(_downloads_files_exists, array("dl" => $file, "sel" => "")); }

$show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head,
                                          "ddownload" => "",
                                           "durl" => "",
                                           "oder" => _or,
                                           "file" => $dl,
                                           "nothing" => "",
                                           "selr_dc" => 'selected="selected"',
                                           "nofile" => _downloads_nofile,
                                           "lokal" => _downloads_lokal,
                                           "what" => _button_value_add,
                                           "do" => "add",
                                           "exist" => _downloads_exist,
                                           "dbeschreibung" => "",
                                           "kat" => _downloads_kat,
                                           "kats" => $kats,
                                           "url" => _downloads_url,
                                           "beschreibung" => _beschreibung,
                                           "download" => _downloads_name));

