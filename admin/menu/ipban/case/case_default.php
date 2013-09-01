<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

//typ: 0 = Off, 1 = GSL, 2 = SysBan, 3 = Ipban
$show = ''; $show_sfs = ''; $show_sys = ''; $show_user = '';
$pager_sfs = ''; $pager_sys = ''; $pager_user = '';

#######################################################
################### Global Spamlist ###################
#######################################################

$count_spam = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '1'",true,false); //Type 1 => Global Stopforumspam.com List
if($count_spam >= 1)
{
    $site = (isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1);
    if($site < 1) $site = 1; $end = $site*10; $start = $end-10;

    $count_spam_nav = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '1' ORDER BY id DESC LIMIT ".$start.", 10",true,false); //Type Userban ROW

    if($start != 0)
        $pager_sfs = '<a href="?admin=ipban&sfs_side='.($site-1).'&sys_side='.(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1).'&ub_side='.(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1).'"><img align="absmiddle" src="../inc/images/previous.png" alt="left" /></a>';
    else
        $pager_sfs = '<img src="../inc/images/previous.png" align="absmiddle" alt="left" class="disabled" />';

    $pager_sfs .=  '&nbsp;'.($start+1).' bis '.($count_spam_nav+$start).'&nbsp;';

    if($count_spam_nav >= 10 )
        $pager_sfs .=  '<a href="?admin=ipban&sfs_side='.($site+1).'&sys_side='.(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1).'&ub_side='.(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1).'"><img align="absmiddle" src="../inc/images/next.png" alt="right" /></a>';
    else
        $pager_sfs .= '<img src="../inc/images/next.png" alt="right" align="absmiddle" class="disabled" />';

    $qry = db("SELECT * FROM ".dba::get('ipban')." WHERE typ = '1' ORDER BY id DESC LIMIT ".$start.",10"); $color = 1;
    while($get = _fetch($qry))
    {
        $data_array = string_to_array(hextobin($get['data']));
        $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
        $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;sfs_side=".($site)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1);
        $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_sfs .= show($dir."/ipban_show_sfs", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "unban" => $unban));
    }
}

//Empty
if(empty($show_sfs))
    $show_sfs = '<tr><td colspan="8" class="contentMainSecond">'._no_entrys.'</td></tr>';

#######################################################
################### System Banlist ####################
#######################################################

$count_sys = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '2'",true,false); //Type 2 => SysBan
if($count_sys >= 1)
{
    $site = (isset($_GET['sys_side']) ? $_GET['sys_side'] : 1);
    if($site < 1) $site = 1; $end = $site*10; $start = $end-10; $pager_sys = '';

    $count_sys_nav = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '2' ORDER BY id DESC LIMIT ".$start.", 10",true,false); //Type GB Spamlist ROW

    if($start != 0)
        $pager_sys = '<a href="?admin=ipban&sys_side='.($site-1).'&sfs_side='.(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1).'&ub_side='.(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1).'"><img align="absmiddle" src="../inc/images/previous.png" alt="left" /></a>';
    else
        $pager_sys = '<img src="../inc/images/previous.png" align="absmiddle" alt="left" class="disabled" />';

    $pager_sys .=  '&nbsp;'.($start+1).' bis '.($count_sys_nav+$start).'&nbsp;';

    if($count_sys_nav >= 10 )
        $pager_sys .=  '<a href="?admin=ipban&sys_side='.($site+1).'&sfs_side='.(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1).'&ub_side='.(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1).'"><img align="absmiddle" src="../inc/images/next.png" alt="right" /></a>';
    else
        $pager_sys .= '<img src="../inc/images/next.png" alt="right" align="absmiddle" class="disabled" />';

    $qry = db("SELECT * FROM ".dba::get('ipban')." WHERE typ = '2' ORDER BY id DESC LIMIT ".$start.", 10"); $color = 1;
    while($get = _fetch($qry))
    {
        $data_array = string_to_array(hextobin($get['data']));
        $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
        $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;sys_side=".($site)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1)."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1);
        $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_sys .= show($dir."/ipban_show_sys", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "unban" => $unban));
    }
}

if(empty($show_sys))
    $show_sys = '<tr><td colspan="8" class="contentMainSecond">'._no_entrys.'</td></tr>';

#######################################################
#################### Users Banlist ####################
#######################################################

$count_user = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '3'",true,false); //Type 3 => Usersban
if($count_user >= 1)
{
    $site = (isset($_GET['ub_side']) ? $_GET['ub_side'] : 1);

    if($site < 1) $site = 1;
    $end = $site*10;
    $start = $end-10;

    $count_user_nav = db("SELECT id FROM ".dba::get('ipban')." WHERE typ = '3' ORDER BY id DESC LIMIT ".$start.", 10",true,false); //Type System Ban ROW

    if($start != 0)
        $pager_user = '<a href="?admin=ipban&ub_side='.($site-1).'&sys_side='.(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1).'&sfs_side='.(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1).'"><img align="absmiddle" src="../inc/images/previous.png" alt="left" /></a>';
    else
        $pager_user = '<img src="../inc/images/previous.png" align="absmiddle" alt="left" class="disabled" />';

    $pager_user .=  '&nbsp;'.($start+1).' bis '.($count_user_nav+$start).'&nbsp;';

    if($count_user_nav >= 10 )
        $pager_user .=  '<a href="?admin=ipban&ub_side='.($site+1).'&sys_side='.(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1).'&sfs_side='.(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1).'"><img align="absmiddle" src="../inc/images/next.png" alt="right" /></a>';
    else
        $pager_user .= '<img src="../inc/images/next.png" alt="right" align="absmiddle" class="disabled" />';

    $qry = db("SELECT * FROM ".dba::get('ipban')." WHERE typ = '3' ORDER BY id DESC LIMIT ".$start.", 10"); $color = 1;
    while($get = _fetch($qry))
    {
        $data_array = string_to_array(hextobin($get['data']));
        $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=edit", "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
        $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;ub_side=".($site)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1);
        $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_user .= show($dir."/ipban_show_user", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "class" => $class, "delete" => $delete, "edit" => $edit, "unban" => $unban));
    }
}

if(empty($show_user))
    $show_user = '<tr><td colspan="8" class="contentMainSecond">'._no_entrys.'</td></tr>';

    ######################################################################################################

$show = show($dir."/ipban", array("show_spam" => $show_sfs,
                                  "show_user" => $show_user,
                                  "show_sys" => $show_sys,
                                  "count_sys" => $count_sys,
                                  "count_user" => $count_user,
                                  "count_spam" => $count_spam,
                                  "pager_sfs" => $pager_sfs,
                                  "pager_user" => $pager_user,
                                  "pager_sys" => $pager_sys));