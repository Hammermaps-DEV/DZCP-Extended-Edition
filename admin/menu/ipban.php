<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

//typ: 0 = Off, 1 = GSL, 2 = SysBan, 3 = Ipban
$where = $where.': '._config_ipban;
$show = ''; $show_sfs = ''; $show_sys = ''; $show_user = '';
$pager_sfs = ''; $pager_sys = ''; $pager_user = '';
switch ($do)
{
    default:
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
            $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
            $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;sfs_side=".($site)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1);
            $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show_sfs .= show($dir."/ipban_show_sfs", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "unban" => $unban));
        }
    }

    //Empty
    if(empty($show_sfs))
        $show_sfs = '<tr><td colspan="8" class="contentMainFirst">'._no_entrys.'</td></tr>';

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
            $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
            $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;sys_side=".($site)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1)."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1);
            $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show_sys .= show($dir."/ipban_show_sys", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "unban" => $unban));
        }
    }

    if(empty($show_sys))
        $show_sys = '<tr><td colspan="8" class="contentMainFirst">'._no_entrys.'</td></tr>';

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
            $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
            $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;ub_side=".($site)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1);
            $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_enable_ipban,array('ip'=>$get['ip'])))));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show_user .= show($dir."/ipban_show_user", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "class" => $class, "delete" => $delete, "edit" => $edit, "unban" => $unban));
        }
    }

    if(empty($show_user))
        $show_user = '<tr><td colspan="8" class="contentMainFirst">'._no_entrys.'</td></tr>';

    ######################################################################################################

    $show = show($dir."/ipban", array(
            "show_spam" => $show_sfs,
            "show_user" => $show_user,
            "show_sys" => $show_sys,
            "count_sys" => $count_sys,
            "count_user" => $count_user,
            "count_spam" => $count_spam,
            "pager_sfs" => $pager_sfs,
            "pager_user" => $pager_user,
            "pager_sys" => $pager_sys));
    break;
    case 'enable':
        $get = db("SELECT id,enable FROM ".dba::get('ipban')." WHERE `id` = ".convert::ToInt($_GET['id']),false,true);
        if($get['enable'] == '1') db("UPDATE ".dba::get('ipban')." SET `enable` = '0' WHERE `id` = ".$get['id'].";");
        else db("UPDATE ".dba::get('ipban')." SET `enable` = '1' WHERE `id` = ".$get['id'].";");
        $show = header("Location: ?admin=ipban&sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1)."&sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1));
    break;

    case 'new':
        $show = show($dir."/ipban_form", array("newhead" => _ipban_new_head, "do" => "add", "ip_set" => '', "info" => '', "what" => _button_value_add));
    break;

    case 'add':
        if(empty($_POST['ip']))
            $show = error(_ip_empty);
        else if(validateIpV4Range($_POST['ip'], '[192].[168].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[127].[0].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[10].[0-255].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[172].[16-31].[0-255].[0-255]'))
            $show = error(_ipban_error_pip);
        else
        {
            if(empty($_POST['info']))
                $info = '*Keine Info*';
            else
                $info = string::encode($_POST['info']);

            $data_array = array();
            $data_array['confidence'] = ''; $data_array['frequency'] = ''; $data_array['lastseen'] = '';
            $data_array['banned_msg'] = $info;
            db("INSERT INTO ".dba::get('ipban')." SET `time` = '".time()."', `ip` = '".$_POST['ip']."', `data` = '".base64_encode(convert::UTF8(array_to_string($data_array)))."', `typ` = 3;");
            $show = info(_ipban_admin_added, "?admin=ipban");
        }
    break;
    case 'delete':
        db("DELETE FROM ".dba::get('ipban')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_ipban_admin_deleted, "?admin=ipban");
    break;
    case 'edit':
        $get = db("SELECT * FROM ".dba::get('ipban')." WHERE id = '".intval($_GET['id'])."'",false,true);
        $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
        $show = show($dir."/ipban_form", array("newhead" => _ipban_edit_head,"do" => "edit_save&amp;id=".$_GET['id']."","ip_set" => $get['ip'],"info" => string::decode($data_array['banned_msg']),"what" => _button_value_edit));
    break;
    case 'edit_save':
        if(empty($_POST['ip']))
            $show = error(_ip_empty);
        else
        {
            $get = db("SELECT id,data FROM ".dba::get('ipban')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
            $data_array['banned_msg'] = string::encode($_POST['info']);
            db("UPDATE ".dba::get('ipban')." SET `ip` = '".$_POST['ip']."', `time` = '".time()."', `data` = '".base64_encode(convert::UTF8(array_to_string($data_array)))."' WHERE id = '".$get['id']."'");
            $show = info(_ipban_admin_edited, "?admin=ipban");
        }
    break;
    case 'search':
        $qry_search = db("SELECT * FROM ".dba::get('ipban')." WHERE ip LIKE '%".$_POST['ip']."%' ORDER BY ip ASC"); //Suche
        $color = 1; $show_search = '';
        while($get = _fetch($qry_search))
        {
            $data_array = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
            $edit =$get['typ'] == '3' ? show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=edit", "title" => _button_title_edit)) : '';
            $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1);
            $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => convSpace(show(_confirm_enable_ipban,array('ip'=>$get['ip']))))));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show_search .= show($dir."/ipban_show_user", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "edit" => $edit, "unban" => $unban));
        }

        if(empty($show_search))
            $show_search = '<tr><td colspan="7" class="contentMainFirst">'._no_entrys.'</td></tr>';

        $show = show($dir."/ipban_search", array("value" => _button_value_save, "show" => $show_search,  "edit" => _editicon_blank, "delete" => _deleteicon_blank ));
    break;
}
