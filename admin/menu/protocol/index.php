<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$where = $where.': '._protocol;
if(isset($_GET['do']) ? ($_GET['do'] == 'deletesingle' ? true : false) : false)
{
    db("DELETE FROM ".dba::get('ipcheck')." WHERE id = '".$_GET['id']."'");
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
elseif(isset($_GET['do']) ? ($_GET['do'] == 'delete' ? true : false) : false)
{
    db("DELETE FROM ".dba::get('ipcheck')." WHERE time != 0");
    $show = info(_protocol_deleted,'?index=admin&amp;admin=protocol');
}
else
{
    if(!empty($_GET['sip']))
    {
        $search = "WHERE ip = '".$_GET['sip']."' AND time != 0 AND what NOT REGEXP 'vid_'";
        $swhat = $_GET['sip'];
    }
    else
    {
        $search = "WHERE time != 0 AND what NOT REGEXP 'vid_'";
        $swhat = _info_ip;
    }

    $maxprot = 30; $color = 1; $show = '';
    $entrys = cnt(dba::get('ipcheck'), $search);
    $page = (isset($_GET['page']) ? $_GET['page'] : 1);
    $qry = db("SELECT * FROM ".dba::get('ipcheck')." ".$search." ORDER BY id DESC LIMIT ".($page - 1)*$maxprot.",".$maxprot."");
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $date = date("d.m.y H:i", $get['time'])._uhr;
        $delete = show("page/button_delete", array("id" => $get['id'], "action" => "index=admin&amp;admin=protocol&amp;do=deletesingle", "title" => _button_title_del));

        if(preg_match("#\(#",$get['what']))
        {
            $a = preg_replace("#^(.*?)\((.*?)\)#is","$1",$get['what']);
            $wid = preg_replace("#^(.*?)\((.*?)\)#is","$2",$get['what']);

            switch ($a)
            {
                case 'dlid': $action = 'wrote <b>comment</b> in <b>downloads</b> with <b>ID</b> '.$wid; break;
                case 'fid': $action = 'wrote in <b>board</b>'; break;
                case 'ncid': $action = 'wrote <b>comment</b> in <b>news</b> with <b>ID</b> '.$wid; break;
                case 'artid': $action = 'wrote <b>comment</b> in <b>article</b> with <b>ID</b> '.$wid; break;
                case 'vid': $action = 'voted <b>poll</b> with <b>ID '.$wid.'</b>'; break;
                case 'mgbid': $action = autor($wid).' got a userbook entry'; break;
                case 'cwid': $action = 'wrote <b>comment</b> in <b>clanwar</b> with <b>ID</b> '.$wid; break;
                case 'createuser': $ids = explode("_", $wid); $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>added</b> user '.autor($ids[1]); break;
                case 'upduser': $ids = explode("_", $wid); $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>edited</b> user '.autor($ids[1]); break;
                case 'deluser':  $ids = explode("_", $wid); $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' <b>deleted</b> user'; break;
                case 'ident': $ids = explode("_", $wid); $action = '<b style="color:red">ADMIN</b> '.autor($ids[0]).' took <b>identity</b> from user '.autor($ids[1]); break;
                case 'logout': $action = autor($wid).' <b>logged out</b>'; break;
                case 'login': $action = autor($wid).' <b>logged in</b>'; break;
                case 'trypwd': $action = 'failed to <b>reset password</b> from '.autor($wid); break;
                case 'pwd': $action = '<b>reseted password</b> from '.autor($wid); break;
                case 'reg': $action = autor($wid).' <b>signed up</b>'; break;
                case 'trylogin': $action = 'failed to <b>login</b> in '.autor($wid).'`s account'; break;
                case 'tryloginpwd': $action = 'failed to <b>login</b> in '.autor($wid).'`s account, password failed!'; break;
                case 'mod_upd': $action = '<b style="color:red">ADMIN</b> '.'Update Mod: <b>'.$wid.'</b>'; break;
                case 'mod_add': $action = '<b style="color:red">ADMIN</b> '.'Install Mod: <b>'.$wid.'</b>'; break;
                case 'mod_del': $action = '<b style="color:red">ADMIN</b> '.'Delete Mod: <b>'.$wid.'</b>'; break;
                case 'doublelog': $ids = explode("_", $wid); $action = '<b style="color:red">ADMIN</b> '.'Double Login "'.autor($ids[0]).'" detected from IP:<b>'.$ids[1].'</b>'; break;
                default: $action = '<b style="color:red">undefined:</b> <b>'.$a.'</b>'; break;
            }
        }
        else
        {
            switch($get['what'])
            {
                case 'gb': $action = 'wrote in <b>guestbook</b>'; break;
                case 'shout': $action = 'wrote in <b>shoutbox</b>'; break;
                default: $action = '<b style="color:red">undefined:</b> <b>'.$a.'</b>'; break;
            }
           }

        $show .= show($dir."/protocol_show", array("datum" => $date,
                                                   "class" => $class,
                                                   "delete" => $delete,
                                                   "user" => $get['ip'],
                                                   "action" => $action));
    }

    if(empty($show))
        $show = show(_no_entrys_yet, array("colspan" => "3"));

    $sip = (!empty($_GET['sip']) ? "&amp;sip=".$_GET['sip'] : '');
    $show = show($dir."/protocol", array("show" => $show,
                                         "del" => _button_title_del_protocol,
                                         "value" => _button_value_search,
                                         "search" => $swhat,
                                         "nav" => nav($entrys,$maxprot,"?index=admin&amp;admin=protocol".$sip)));
}