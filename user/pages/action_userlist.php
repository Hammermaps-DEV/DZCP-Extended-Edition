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
    ################
    ## Userliste  ##
    ################
    $where = _site_ulist;
    $maxuserlist = settings('m_userlist');
    $search = (isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : _nick);

    if(checkme() == 4 || permission('editusers') || permission('activateusers'))
        $sql_filter = "level != '0' OR ( level = '0' AND actkey IS NOT NULL )";
    else
        $sql_filter = "level != '0'";

    switch(isset($_GET['show']) ? $_GET['show'] : '')
    {
        case 'search': $qry = " WHERE nick LIKE '%".$search."%' AND ".$sql_filter." LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'newreg': $qry = " WHERE regdatum > '".$_SESSION['lastvisit']."' AND ".$sql_filter." ORDER BY regdatum DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'lastlogin': $qry = " WHERE ".$sql_filter." ORDER BY time DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'lastreg': $qry = " WHERE ".$sql_filter." ORDER BY regdatum DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'online': $qry = " WHERE ".$sql_filter." ORDER BY time DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'country': $qry = " WHERE ".$sql_filter." ORDER BY country LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'sex': $qry = " WHERE ".$sql_filter." ORDER BY sex DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
        case 'banned':
            $sql_filter = "level == '0' AND actkey IS NULL";
            $qry = " WHERE ".$sql_filter." ORDER BY id LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist;
        break;
        default: $qry = " WHERE ".$sql_filter." ORDER BY level DESC LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist; break;
    }

    $entrys = cnt(dba::get('users'),$qry);
    if(!_rows(($qry = db("SELECT id,nick,level,email,hp,xfire,bday,sex,icq,status,position,regdatum,steamurl,skype,xbox,psn,origin,bnet FROM ".dba::get('users').$qry))))
        $userliste = show(_no_entrys_yet_all, array("colspan" => "12"));
    else
    {
        $userliste = '';
        while($get = _fetch($qry))
        {
            $hp = (empty($get['hp']) ? '-' : show(_hpicon, array("hp" => $get['hp'])));
            $sex = ($get['sex'] == 1 ? _maleicon : ($get['sex'] == 2 ? _femaleicon : "-"));
            $getstatus = ($get['status'] ? _aktiv_icon : _inaktiv_icon);
            $status = (data($get['id'], "level") > 1 ? $getstatus : ''); $color = 1;
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $edit = ""; $delete = "";
            if(permission("editusers"))
            {
                $edit = str_replace("&amp;id=","",show("page/button_edit", array("id" => "", "action" => "index=user&action=admin&amp;edit=".$get['id'], "title" => _button_title_edit)));
                $delete = show("page/button_delete", array("id" => $get['id'], "action" => "index=user&action=admin&amp;do=delete", "title" => _button_title_del));
            }

            $icq = "-";
            if(!empty($get['icq']))
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$get['icq'].'" target="_blank">'.show(_icqstatus, array("uin" => $get['icq'])).'</a>';

            $xfire = '-';
            if(!empty($get['xfire']) && xfire_enable)
            $xfire = '<div id="infoXfire_'.md5(string::decode($get['xfire'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoXfire_'.md5(string::decode($get['xfire'])).'","xfire","&username='.string::decode($get['xfire']).'");</script></div>';

            $steam = '-';
            if(!empty($get['steamurl']) && steam_enable)
            $steam = '<div id="infoSteam_'.md5(string::decode($get['steamurl'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoSteam_'.md5(string::decode($get['steamurl'])).'","steam","&steamid='.string::decode($get['steamurl']).'");</script></div>';

            $skype = '-';
            if(!empty($get['skype']) && skype_enable)
                $skype = '<div id="infoSkype_'.md5(string::decode($get['skype'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoSkype_'.md5(string::decode($get['skype'])).'","skype","&username='.string::decode($get['skype']).'");</script></div>';

            $xbox = '-';
            if(!empty($get['xbox']) && xbox_enable)
                $xbox = '<div id="infoXbox_'.md5(string::decode($get['xbox'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoXbox_'.md5(string::decode($get['xbox'])).'","xbox","&xboxid='.string::decode($get['xbox']).'");</script></div>';

            $psn = '-';
            if(!empty($get['psn']) && psn_enable)
                $psn = '<div id="infoPSN_'.md5(string::decode($get['psn'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoPSN_'.md5(string::decode($get['psn'])).'","psn","&psnid='.string::decode($get['psn']).'");</script></div>';

            $origin = '-';
            if(!empty($get['origin']) && origin_enable)
                $origin = '<div id="infoOrigin_'.md5(string::decode($get['origin'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoOrigin_'.md5(string::decode($get['origin'])).'","origin","&originid='.string::decode($get['origin']).'");</script></div>';

            $bnet = '-';
            if(!empty($get['bnet']) && bnet_enable)
                $bnet = '<div id="infoBnet_'.md5(string::decode($get['bnet'])).'">
            <div style="width:100%;text-align:center"><img src="inc/images/ajax-loader-mini.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">DZCP.initDynLoader("infoBnet_'.md5(string::decode($get['bnet'])).'","bnet","&bnetid='.string::decode($get['bnet']).'");</script></div>';

            $userliste .= show($dir."/userliste_show", array("nick" => autor($get['id'],'','',10),
                                                             "level" => getrank($get['id']),
                                                             "status" => $status,
                                                             "age" => getAge($get['bday']),
                                                             "mf" => $sex,
                                                             "edit" => $edit,
                                                             "delete" => $delete,
                                                             "class" => $class,
                                                             "icq" => $icq,
                                                             "skype" => $skype,
                                                             "xbox" => $xbox,
                                                             "psn" => $psn,
                                                             "origin" => $origin,
                                                             "bnet" => $bnet,
                                                             "icquin" => $get['icq'],
                                                             "onoff" => onlinecheck($get['id']),
                                                             "hp" => $hp,
                                                             "xfire" => $xfire,
                                                             "steam" => $steam));
        } //while end
    }

    $edel = (permission("editusers") ? '<td class="contentMainTop" colspan="2">&nbsp;</td>' : '');
    $seiten = nav($entrys,$maxuserlist,"?index=user&amp;action=userlist&show=".(isset($_GET['show']) ? $_GET['show'] : 1));
    $show_entrys = show(_userlist_counts, array("cnt_full" => $entrys." ".( $entrys >= 2 ? _users : _user ), "cnt" => _rows($qry)));
    $index = show($dir."/userliste", array("show_entrys" => $show_entrys, "edel" => $edel, "search" => $search, "nav" => $seiten, "show" => $userliste)); //Index Out
}