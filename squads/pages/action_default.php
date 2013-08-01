<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('squads')." WHERE team_show = 1 ORDER BY pos");
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $squad = show(_gameicon, array("icon" => $get['icon'])).' '.string::decode($get['name']); $style = '';

        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($get['id']).'.'.$end))
            {
                $style = 'text-align:center;padding:0';
                $squad = '<img src="../inc/images/uploads/squads/'.convert::ToInt($get['id']).'.'.$end.'" alt="'.string::decode($get['name']).'" />';
                break;
            }
        }

        $show .= show($dir."/squads_show", array("id" => $get['id'],
                "squad" => $squad,
                "style" => $style,
                "class" => $class,
                "beschreibung" => bbcode::parse_html($get['beschreibung']),
                "squadname" => string::decode($get['name'])
        ));
    }

    $cntm = db("SELECT * FROM ".dba::get('squaduser')." GROUP BY user");
    $weare = show(_member_squad_weare, array("cm" => _rows($cntm),
            "cs" => cnt(dba::get('squads'), "WHERE team_show = 1")));

    $index = show($dir."/squads", array("squadhead" => _member_squad_head,
            "weare" => $weare,
            "show" => $show));
}