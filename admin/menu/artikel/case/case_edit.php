<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

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
        db("UPDATE ".dba::get('artikel')."
                    SET `kat`    = '".convert::ToInt($_POST['kat'])."',
                        `titel`  = '".string::encode($_POST['titel'])."',
                        `text`   = '".string::encode($_POST['artikel'])."',
                        `comments` = '".convert::ToInt($_POST['comments'])."',
                        `link1`  = '".string::encode($_POST['link1'])."',
                        `link2`  = '".string::encode($_POST['link2'])."',
                        `link3`  = '".string::encode($_POST['link3'])."',
                        `url1`   = '".links($_POST['url1'])."',
                        `url2`   = '".links($_POST['url2'])."',
                        `url3`   = '".links($_POST['url3'])."'
                    WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_artikel_edited, "?index=admin&amp;admin=artikel");
    }
}

if(empty($show))
{
    $get = db("SELECT * FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $qryk = db("SELECT * FROM ".dba::get('newskat').""); $kat = '';
    while($getk = _fetch($qryk))
    {
        $sel = ((isset($_POST['kat']) ? $_POST['kat'] : $get['kat']) == $getk['id'] ? 'selected="selected"' : '');
        $kat .= show(_select_field, array("value" => $getk['id'], "sel" => $sel, "what" => string::decode($getk['kategorie'])));
    }

    $do = show(_artikel_edit_link, array("id" => $_GET['id']));
    $selr_ac = ($get['comments'] ? 'selected="selected"' : '');
    $show = show($dir."/artikel_form", array("head" => _artikel_edit,
                                             "autor" => autor(),
                                             "kat" => $kat,
                                             "do" => $do,
                                             "artikeltext" => (isset($_POST['artikel']) ? string::decode($_POST['artikel']) : string::decode($get['text'])),
                                             "titel" => (isset($_POST['titel']) ? string::decode($_POST['titel']) : string::decode($get['titel'])),
                                             "link1" => (isset($_POST['link1']) ? string::decode($_POST['link1']) : string::decode($get['link1'])),
                                             "link2" => (isset($_POST['link2']) ? string::decode($_POST['link2']) : string::decode($get['link2'])),
                                             "link3" => (isset($_POST['link3']) ? string::decode($_POST['link3']) : string::decode($get['link3'])),
                                             "url1" => (isset($_POST['url1']) ? $_POST['url1'] : $get['url1']),
                                             "url2" => (isset($_POST['url2']) ? $_POST['url2'] : $get['url2']),
                                             "url3" => (isset($_POST['url3']) ? $_POST['url3'] : $get['url3']),
                                             "selr_ac" => $selr_ac,
                                             "error" => "",
                                             "button" => _button_value_edit));
}