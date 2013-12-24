<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$error = '';
if(isset($_POST['link']))
{
    if(empty($_POST['link']) || empty($_POST['beschreibung']) || (isset($_POST['banner']) && empty($_POST['blink'])))
    {
        if(empty($_POST['link']))
            $error = _links_empty_link;
        else if(empty($_POST['beschreibung']))
            $error = _links_empty_beschreibung;
        else if(empty($_POST['blink']))
            $error = _links_empty_banner_link;
    }

    if(empty($error))
    {
        db("UPDATE `".dba::get('links')."` SET `url` = '".string::encode($_POST['link'])."',
                                               `blink` = '".string::encode($_POST['blink'])."',
                                               `banner` = ".(isset($_POST['banner']) ? convert::ToInt($_POST['banner']) : '0').",
                                               `beschreibung` = '".string::encode($_POST['beschreibung'])."'
                                           WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_link_edited, "?index=admin&amp;admin=links");
    }
}

if(empty($show))
{
    $get = db("SELECT * FROM ".dba::get($_GET['type'])." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $show = show($dir."/form_links", array('head' => _links_admin_head_edit,
            'error' => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
            'bchecked' => (isset($_POST['banner']) ? ($_POST['banner'] == '1' ? 'checked="checked"' : '') : ($get['banner'] ? 'checked="checked"' : '')),
            'bnone' => (isset($_POST['banner']) ? ($_POST['banner'] == '1' ? 'display:table-row;' : 'display:none;') : ( $get['banner'] ? 'display:table-row;' : 'display:none;')),
            'llink' => (isset($_POST['link']) ? $_POST['link'] : string::decode($get['url'])),
            'lbeschreibung' => (isset($_POST['beschreibung']) ? $_POST['beschreibung'] : string::decode($get['beschreibung'])),
            'blink' => (isset($_POST['blink']) ? $_POST['blink'] : string::decode($get['blink'])),
            'what' => _button_value_edit,
            'do' => 'edit&amp;id='.convert::ToInt($_GET['id'])));
}