<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['download']) || empty($_POST['url']))
{
	if(empty($_POST['download'])) $show = error(_downloads_empty_download);
	elseif(empty($_POST['url']))  $show = error(_downloads_empty_url);
} else {
	if(preg_match("#^www#i",$_POST['url'])) $dl = links($_POST['url']);
	else                                    $dl = string::encode($_POST['url']);

	$qry = db("UPDATE ".dba::get('downloads')."
                     SET `download`     = '".string::encode($_POST['download'])."',
                         `url`          = '".$dl."',
                         `comments`     = '".convert::ToInt($_POST['comments'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                         `date`         = '".time()."',
                         `kat`          = '".convert::ToInt($_POST['kat'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

	$show = info(_downloads_edited, "?admin=dladmin");
}
