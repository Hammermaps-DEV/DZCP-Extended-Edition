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
    if(empty($_POST['event']) || empty($_POST['url']))
    {
        if(empty($_POST['event']))
            $error = _awards_empty_event;
        else if(empty($_POST['url']))
            $error = _awards_empty_url;
    }

    $place = (empty($_POST['place']) ? '-' : $_POST['place']);
    $prize = (empty($_POST['prize']) ? '-' : $_POST['prize']);
    $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
    $qry = db("UPDATE ".dba::get('awards')."
                       SET `date`   = '".convert::ToInt($datum)."',
                           `squad`  = '".convert::ToInt($_POST['squad'])."',
                           `event`  = '".string::encode($_POST['event'])."',
                           `url`    = '".string::encode($_POST['url'])."',
                           `place`  = '".string::encode($place)."',
                           `prize`  = '".string::encode($prize)."'
                       WHERE id = '".convert::ToInt($_GET['id'])."'");

    $show = info(_awards_admin_edited, "?admin=awards");
}

if(empty($show))
{
    $get = db("SELECT * FROM ".dba::get('awards')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $qry = db("SELECT id,name,game,icon FROM ".dba::get('squads')." ORDER BY game ASC"); $squads = '';
    while($get_squad = _fetch($qry))
    {
        $squads .= show(_awards_admin_edit_select_field_squads, array("name" => string::decode($get_squad['name']), "game" => string::decode($get_squad['game']), "icon" => $get_squad['icon'], "sel" => ($get['squad'] == $get_squad['id'] ? 'selected="selected"' : ''), "id" => $get_squad['id']));
    }

    $time = (isset($_POST['m']) && isset($_POST['t']) && isset($_POST['j']) ? mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']) : $get['date']);
    $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$time)), "month" => dropdown("month",date("m",$time)), "year" => dropdown("year",date("Y",$time))));
    $show = show($dir."/form_awards", array("head" => _awards_admin_head_edit,
            "squads" => $squads,
            "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
            "dropdown_date" => $dropdown_date,
            "do" => "edit&amp;id=".$_GET['id']."",
            "what" => _button_value_edit,
            "award_event" => isset($_POST['event']) ? $_POST['event'] : string::decode($get['event']),
            "award_url" => isset($_POST['url']) ? $_POST['url'] : string::decode($get['url']),
            "award_place" => isset($_POST['place']) ? $_POST['place'] : string::decode($get['place']),
            "award_prize" => isset($_POST['prize']) ? $_POST['prize'] : string::decode($get['prize'])));
}