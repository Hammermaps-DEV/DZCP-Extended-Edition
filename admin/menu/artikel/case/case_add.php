<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();
$error = '';
if($_POST)
{
    if(empty($_POST['titel']) || empty($_POST['artikel']))
    {
        if(empty($_POST['titel']))
            $error = show("errors/errortable", array("error" => _empty_artikel_title));
        else if(empty($_POST['artikel']))
            $error = show("errors/errortable", array("error" => _empty_artikel));
    }
	else
	{
		db("INSERT INTO ".dba::get('artikel')."
                    SET `autor`  = '".userid()."',
                        `kat`    = '".convert::ToInt($_POST['kat'])."',
                        `titel`  = '".string::encode($_POST['titel'])."',
                        `text`   = '".string::encode($_POST['artikel'])."',
                        `comments` = '".convert::ToInt($_POST['comments'])."',
                        `link1`  = '".string::encode($_POST['link1'])."',
                        `link2`  = '".string::encode($_POST['link2'])."',
                        `link3`  = '".string::encode($_POST['link3'])."',
                        `url1`   = '".links($_POST['url1'])."',
                        `url2`   = '".links($_POST['url2'])."',
                        `url3`   = '".links($_POST['url3'])."'");

		$show = info(_artikel_added, "?admin=artikel");
	}
}

if(empty($show))
{
	$qryk = db("SELECT * FROM ".dba::get('newskat').""); $kat = '';
	while($getk = _fetch($qryk))
	{
		$sel = ((isset($_POST['kat']) ? $_POST['kat'] : '0') == $getk['id'] ? 'selected="selected"' : '');
		$kat .= show(_select_field, array("value" => $getk['id'], "sel" => $sel, "what" => string::decode($getk['kategorie'])));
	}

	$selr_ac = ($_POST['comments'] ? 'selected="selected"' : '');
	$show = show($dir."/artikel_form", array("head" => _artikel_add,
                                             "autor" => autor(),
                                             "kat" => $kat,
                                             "do" => "add",
                                             "selr_ac" => $selr_ac,
                                             "error" => $error,
                                             "titel" => (isset($_POST['titel']) ? string::decode($_POST['titel']) : ''),
                                             "artikeltext" => (isset($_POST['artikel']) ? string::decode($_POST['artikel']) : ''),
                                             "link1" => (isset($_POST['link1']) ? string::decode($_POST['link1']) : ''),
                                             "link2" => (isset($_POST['link2']) ? string::decode($_POST['link2']) : ''),
                                             "link3" => (isset($_POST['link3']) ? string::decode($_POST['link3']) : ''),
                                             "url1" => (isset($_POST['url1']) ? $_POST['url1'] : ''),
                                             "url2" => (isset($_POST['url2']) ? $_POST['url2'] : ''),
                                             "url3" => (isset($_POST['url3']) ? $_POST['url3'] : ''),
                                             "button" => _button_value_add));
}
