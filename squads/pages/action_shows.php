﻿<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if(!empty($_GET['showsquad'])) header('Location: ?action=shows&id='.intval($_GET['showsquad']));
    else if(!empty($_GET['show'])) header('Location: ?action=shows&id='.intval($_GET['show']));

    $get = _fetch(db("SELECT * FROM ".$db['squads']." WHERE `id` = '".intval($_GET['id'])."'"));
    $qrym = db("SELECT s1.user,s1.squad,s2.id,s2.nick,s2.icq,s2.email,s2.xfire,s2.rlname,
                  s2.level,s2.bday,s2.hp,s3.posi,s4.pid
                  FROM ".$db['squaduser']." AS s1
                  LEFT JOIN ".$db['users']." AS s2
                  ON s2.id=s1.user
                  LEFT JOIN ".$db['userpos']." AS s3
                  ON s3.squad=s1.squad AND s3.user=s1.user
                  LEFT JOIN ".$db['pos']." AS s4
                  ON s4.id=s3.posi
                  WHERE s1.squad='".intval($_GET['id'])."'
                  ORDER BY s4.pid, s2.nick");

    $member = "";
    $t = 1;
    $c = 1;
    while($getm = _fetch($qrym))
    {
        $cntall = cnt($db['squaduser'], " WHERE squad= '".$get['id']."'");

        if($getm['icq'] == 0)
        {
            $icq = "-";
            $icqnr = "&nbsp;";
        } else {
            $icq = show(_icqstatus, array("uin" => $getm['icq']));
            $icqnr = $getm['icq'];
        }

        $class = ($color % 2) ? "contentMainFirst" : "contentMainSecond"; $color++;
        $nick = autor($getm['user'],'','','','','&amp;sq='.$getm['squad']);

        if(!empty($getm['rlname']))
        {
            $real = explode(" ", re($getm['rlname']));
            $nick = '<b>'.$real[0].' &#x93;</b> '.$nick.' <b>&#x94; '.$real[1].'</b>';
        }


        $member .= show($dir."/squads_member", array("icqs" => $icq,
                "icq" => $icqnr,
                "email" => $email,
                "hlsw" => $hlsw,
                "emails" => eMailAddr($getm['email']),
                "id" => $getm['user'],
                "class" => $class,
                "nick" => $nick,
                "onoff" => onlinecheck($getm['id']),
                "posi" => getrank($getm['id'],$getm['squad']),
                "pic" => userpic($getm['id'],60,80)));
    }

    $squad = re($get['name']);
    foreach($picformat AS $end)
    {
        if(file_exists(basePath.'/inc/images/squads/'.intval($get['id']).'.'.$end))
        {
            $style = 'padding:0;';
            $squad = '<img src="../inc/images/squads/'.intval($get['id']).'.'.$end.'" alt="'.re($get['name']).'" />';
            break;
        }
    }

    $index = show($dir."/squads_full", array("member" => (empty($member) ? _member_squad_no_entrys : $member),
            "desc" => empty($get['beschreibung']) ? '' : '<tr><td class="contentMainSecond">'.bbcode($get['beschreibung']).'</td></tr>',
            "squad" => $squad,
            "style" => $style,
            "back" => _error_back,
            "id"   => intval($_GET['id'])));
}
?>