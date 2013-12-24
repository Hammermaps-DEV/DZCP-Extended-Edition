<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(isset($_POST) && empty($_POST['name']) || empty($_POST['link']) || empty($_POST['beschreibung']))
{
    if(empty($_POST['beschreibung']))
        $error = show("errors/errortable", array("error" => _sponsors_empty_beschreibung));
    else if(empty($_POST['link']))
        $error = show("errors/errortable", array("error" => _sponsors_empty_link));
    else if(empty($_POST['name']))
        $error = show("errors/errortable", array("error" => _sponsors_empty_name));

    $pos = db("SELECT pos,name FROM ".dba::get('sponsoren')." ORDER BY pos");
    $mp = db("SELECT pos FROM ".dba::get('sponsoren')." WHERE name != '".$_POST['posname']."' AND pos = '".convert::ToInt(($_POST['position']-1))."'",false,true); $positions = '';
    while($getpos = _fetch($pos))
    {
        if($getpos['name'] != $_POST['posname'])
              $positions .= show(_select_field, array("value" => $getpos['pos']+1, "what" => _nach.' '.string::decode($getpos['name']), "sel" => ($getpos['pos'] == $mp['pos'] ? 'selected="selected"' : '')));
    }

    $schecked = ""; $snone = "none";
    if(isset($_POST['site']))
    {
        $schecked = "checked=\"checked\"";
        $snone = "";
    }

    $bchecked = ""; $bnone = "none";
    if(isset($_POST['banner']))
    {
        $bchecked = "checked=\"checked\"";
        $bnine = "";
    }

    $xchecked = ""; $xnone = "none";
    if(isset($_POST['box']))
    {
        $xchecked = "checked=\"checked\"";
        $xnone = "";
    }

    $show = show($dir."/form_sponsors", array("error" => $error,
                                              "sname" => $_POST['name'],
                                              "slink" => $_POST['link'],
                                              "sbeschreibung" => string::decode($_POST['beschreibung']),
                                              "schecked" => $schecked,
                                              "snone" => $snone,
                                              "site_link" => $_POST['slink'],
                                              "sitepic" => "",
                                              "bchecked" => $bchecked,
                                              "bnone" => $bnone,
                                              "banner_link" => $_POST['blink'],
                                              "bannerpic" => "",
                                              "xchecked" => $xchecked,
                                              "xnone" => $xnone,
                                              "box_link" => $_POST['xlink'],
                                              "boxpic" => "",
                                              "positions" => $positions,
                                              "posname" => $_POST['posname'],
                                              "what" => _button_value_add,
                                              "do" => "add"));


}
else
{
    $sign = ($_POST['position'] == 1 || $_POST['position'] == 2 ? ">= " : "> ");
    db("UPDATE ".dba::get('sponsoren')." SET `pos` = pos+1 WHERE pos ".$sign." '".convert::ToInt($_POST['position'])."'");

    db("INSERT INTO ".dba::get('sponsoren')."
                 SET `name`         = '".string::encode($_POST['name'])."',
                     `link`         = '".string::encode(links($_POST['link']))."',
                     `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                     `site`         = '".convert::ToInt($_POST['site'])."',
                     `slink`        = '".string::encode(links($_POST['slink']))."',
                     `banner`       = '".convert::ToInt($_POST['banner'])."',
                     `blink`        = '".string::encode(links($_POST['blink']))."',
                     `box`       	= '".convert::ToInt($_POST['box'])."',
                     `xlink` 		= '".string::encode($_POST['xlink'])."',
                     `pos`    		= '".convert::ToInt($_POST['position'])."'");

    $id = database::get_insert_id();

    //File Upload
    if($_POST['site'] == '1')
        sponsoren_uploader('sdata',$id);

    if($_POST['banner'] == '1')
        sponsoren_uploader('bdata',$id);

    if($_POST['box'] == '1')
        sponsoren_uploader('xdata',$id);

    $show = info(_sponsor_added, "?index=admin&amp;admin=sponsors");
}