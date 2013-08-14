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
    if(empty($_POST['event']) || empty($_POST['url']))
    {
        if(empty($_POST['event']))
            $error = _awards_empty_event;
        else if(empty($_POST['url']))
            $error = _awards_empty_url;
    }
    else
    {
        $place = (empty($_POST['place']) ? '-' : $_POST['place']);
        $prize = (empty($_POST['prize']) ? '-' : $_POST['prize']);
        $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
        $qry = db("INSERT INTO ".dba::get('awards')."
                         SET `date`     = '".convert::ToInt($datum)."',
                             `postdate` = '".time()."',
                             `squad`    = '".convert::ToInt($_POST['squad'])."',
                             `event`    = '".string::encode($_POST['event'])."',
                             `url`      = '".string::encode($_POST['url'])."',
                             `place`    = '".string::encode($place)."',
                             `prize`    = '".string::encode($prize)."'");

        $show = info(_awards_admin_added, "?admin=awards");
    }
}

if(empty($show))
{
    $qry = db("SELECT * FROM ".dba::get('squads')." ORDER BY game ASC"); $squads = '';
    while($get = _fetch($qry))
    {
        $squads .= show(_awards_admin_add_select_field_squads, array("name" => string::decode($get['name']), "game" => string::decode($get['game']), "icon" => $get['icon'], "id" => $get['id']));
    }

    $time = (isset($_POST['m']) && isset($_POST['t']) && isset($_POST['j']) ? mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']) : time());
    $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$time)), "month" => dropdown("month",date("m",$time)), "year" => dropdown("year",date("Y",$time))));
    $show = show($dir."/form_awards", array("head" => _awards_admin_head_add,
                                            "squads" => $squads,
                                            "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                            "dropdown_date" => $dropdown_date,
                                            "do" => "new",
                                            "what" => _button_value_add,
                                            "award_event" => isset($_POST['event']) ? $_POST['event'] : '',
                                            "award_url" => isset($_POST['url']) ? $_POST['url'] : '',
                                            "award_place" => isset($_POST['place']) ? $_POST['place'] : '',
                                            "award_prize" => isset($_POST['prize']) ? $_POST['prize'] : ''));
}