<?php
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

########################
## Useradministration ##
########################
if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if(checkme() == "unlogged")
        $index = error(_error_have_to_be_logged);
    else
    {
        if(isset($_GET['edit']) && data(convert::ToInt($_GET['edit']), "level") == 4 && userid() != convert::ToInt($rootAdmin))
            $index = error(_error_edit_admin);
        else if(isset($_GET['edit']) && convert::ToInt($_GET['edit']) == userid())
        {
            $qrysq = db("SELECT id,name FROM ".dba::get('squads')." ORDER BY pos"); $esquads = '';
            while($getsq = _fetch($qrysq))
            {
                $qrypos = db("SELECT id,position FROM ".dba::get('pos')." ORDER BY pid"); $posi = '';
                while($getpos = _fetch($qrypos))
                {
                    $sel = (db("SELECT id FROM ".dba::get('userpos')." WHERE posi = '".$getpos['id']."' AND squad = '".$getsq['id']."' AND user = '".convert::ToInt($_GET['edit'])."'",true) ? 'selected="selected"' : '');
                    $posi .= show(_select_field_posis, array("value" => $getpos['id'], "sel" => $sel, "what" => string::decode($getpos['position'])));
                }

                $check = (db("SELECT squad FROM ".dba::get('squaduser')." WHERE user = '".convert::ToInt($_GET['edit'])."' AND squad = '".$getsq['id']."'",true) ? 'checked="checked"' : '');
                $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => $check, "eposi" => $posi, "squad" => string::decode($getsq['name'])));
                unset($posi,$check);
            }

            $index = show($dir."/admin_self", array("showpos" => getrank($_GET['edit']), "esquad" => $esquads, "value" => _button_value_edit));
        }
        else
        {
            switch($do)
            {
                case 'identy':
                    if((data(convert::ToInt($_GET['id']), "level") == 4 && userid() != convert::ToInt($rootAdmin)) || convert::ToInt($_GET['id']) == convert::ToInt($rootAdmin))
                        $index = error(_identy_admin);
                    else
                    {
                        $msg = show(_admin_user_get_identy, array("nick" => autor($_GET['id'])));
                        $index = info($msg, "?action=user&amp;id=".convert::ToInt($_GET['id'])."");

                        ## Ereignis in den Adminlog schreiben ##
                        wire_ipcheck("ident(".userid()."_".convert::ToInt($_GET['id']).")");

                        ## User aus der Datenbank suchen ##
                        if(!empty($_GET['id']))
                        {
                            $sql = db("SELECT id,pwd,time FROM ".dba::get('users')." WHERE id = '".convert::ToInt($_GET['id'])."' AND level != '0'");
                            if(_rows($sql))
                            {
                                $get = _fetch($sql);

                                ## Schreibe Werte in die Server Sessions ##
                                $_SESSION['id']         = $get['id'];
                                $_SESSION['pwd']        = $get['pwd'];
                                $_SESSION['lastvisit']  = $get['time'];
                                $_SESSION['ip']         = visitorIp();

                                ## Aktualisiere Datenbank ##
                                db("UPDATE ".dba::get('users')." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".visitorIp()."', `pkey` = '' WHERE id = '".$get['id']."'");
                            }
                        }
                    }
                break;
                case 'update':
                    if($_POST)
                    {
                        $edituser = convert::ToInt($_GET['user']);

                        // Permissions Update
                        if(empty($_POST['perm']))
                            $_POST['perm'] = array();

                        $qry_fields = db("SHOW FIELDS FROM ".dba::get('permissions')); $sql_update = '';
                        while($get = _fetch($qry_fields))
                        {
                            if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum')
                            {
                                $sql = array_key_exists('p_'.$get['Field'], $_POST['perm']) ? $get['Field'].' = 1' : $get['Field'].' = 0';
                                $sql_update .= $sql.', ';
                            }
                        }

                        // Check User Permissions is exists
                        if(!db('SELECT id FROM `'.dba::get('permissions').'` WHERE `user` = '.$edituser.' LIMIT 1',true))
                            db("INSERT INTO ".dba::get('permissions')." SET `user` = '".convert::ToInt($edituser)."';");

                        // Update Permissions
                        db('UPDATE '.dba::get('permissions').' SET '.substr($sql_update, 0, -2).' WHERE user = '.$edituser);

                        // Internal Boardpermissions Update
                        if(empty($_POST['board']))
                            $_POST['board'] = array();

                        // Cleanup
                        $sql = db('SELECT id,forum FROM `'.dba::get('f_access').'` WHERE `user` = '.$edituser);
                        while($get = _fetch($sql))
                        { if(!array_var_exists($get['forum'],$_POST['board'])) db('DELETE FROM `'.dba::get('f_access').'` WHERE `id` = '.$get['id']); }

                        //Neuer Eintrag
                        if(count($_POST['board']) >= 1)
                        {
                            foreach($_POST['board'] AS $boardpem)
                            { if(!db("SELECT * FROM `".dba::get('f_access')."` WHERE `user` = ".$edituser." AND `forum` = ".$boardpem,true)) db("INSERT INTO ".dba::get('f_access')." SET `user` = '".$edituser."', `forum` = '".$boardpem."'"); }
                        }

                        //intforum gegen f_access tabelle tauschen *intforum entfernen
                        //db("UPDATE `".dba::get('permissions')."` SET `intforum` = '".(!count($_POST['board']) ? '0' : '1')."' WHERE `id` =".$edituser);

                        // Squads Update
                        db("DELETE FROM ".dba::get('squaduser')." WHERE user = '".$edituser."'");
                        db("DELETE FROM ".dba::get('userpos')." WHERE user = '".$edituser."'");

                        $sq = db("SELECT id FROM ".dba::get('squads')."");
                        while($getsq = _fetch($sq))
                        {
                            if(isset($_POST['squad'.$getsq['id']]))
                               db("INSERT INTO ".dba::get('squaduser')." SET `user` = '".$edituser."', `squad`  = '".convert::ToInt($_POST['squad'.$getsq['id']])."'");

                            if(isset($_POST['squad'.$getsq['id']]))
                                db("INSERT INTO ".dba::get('userpos')." SET `user` = '".$edituser."', `posi`   = '".convert::ToInt($_POST['sqpos'.$getsq['id']])."', `squad`  = '".convert::ToInt($getsq['id'])."'");
                        }

                        if(isset($_POST['passwd']) && !empty($_POST['passwd']))
                        {
                            $default_pwd_encoder = settings('default_pwd_encoder');
                            if($_POST['passwd']) $newpwd = "`pwd` = '".pass_hash($_POST['passwd'],$default_pwd_encoder)."', `pwd_encoder` = ".convert::ToInt($default_pwd_encoder).",";
                        }

                        $level_sql = ($_POST['level'] == 4 ? (data(userid(), "level") == 4 || userid() == convert::ToInt($rootAdmin) ? ", `level`  = '".convert::ToInt($_POST['level'])."' " : '') : ", `level`  = '".convert::ToInt($_POST['level'])."' ");
                        db("UPDATE ".dba::get('users')." SET ".(isset($_POST['passwd']) && !empty($_POST['passwd']) ? $newpwd : '')."
                           `nick`   = '".convert::ToString(string::encode($_POST['nick']))."',
                           `email`  = '".convert::ToString($_POST['email'])."',
                           `user`   = '".convert::ToString($_POST['loginname'])."',
                           `listck` = '".convert::ToInt(isset($_POST['listck']) ? $_POST['listck'] : 0)."'
                           ".$level_sql."
                          WHERE id = ".$edituser);

                        ## Ereignis in den Adminlog schreiben ##
                        wire_ipcheck("upduser(".userid()."_".$edituser.")");
                    }

                    $index = info(_admin_user_edited, "?action=admin&amp;edit=".$edituser);
                break;
                case 'updateme':
                    // Squads Update
                    db("DELETE FROM ".dba::get('squaduser')." WHERE user = '".userid()."'");
                    db("DELETE FROM ".dba::get('userpos')." WHERE user = '".userid()."'");

                    $sq = db("SELECT id FROM ".dba::get('squads')."");
                    while($getsq = _fetch($sq))
                    {
                        if(isset($_POST['squad'.$getsq['id']]))
                            db("INSERT INTO ".dba::get('squaduser')." SET `user` = '".userid()."', `squad`  = '".convert::ToInt($_POST['squad'.$getsq['id']])."'");

                        if(isset($_POST['squad'.$getsq['id']]))
                            db("INSERT INTO ".dba::get('userpos')." SET `user` = '".userid()."', `posi`   = '".convert::ToInt($_POST['sqpos'.$getsq['id']])."', `squad`  = '".convert::ToInt($getsq['id'])."'");
                    }

                    $index = info(_admin_user_edited, "?action=admin&amp;edit=".userid()."");
                break;
                case 'delete':
                    $index = show(_user_delete_verify, array("user" => autor(convert::ToInt($_GET['id'])), "id" => convert::ToInt($_GET['id'])));

                    if(isset($_GET['verify']) && $_GET['verify'] == "yes" && (userid() == convert::ToInt($_GET['id']) || checkme() == 4))
                    {
                        if((checkme(convert::ToInt($_GET['id'])) == 4 || checkme(convert::ToInt($_GET['id'])) == 3) && convert::ToInt($_GET['id']) != convert::ToInt($rootAdmin))
                            $index = error(_user_cant_delete_admin, '2');
                        else if (convert::ToInt($_GET['id']) == convert::ToInt($rootAdmin))
                            $index = error(_user_cant_delete_radmin, '2');
                        else
                        {
                            ## Ereignis in den Adminlog schreiben ##
                            wire_ipcheck("deluser(".userid()."_".convert::ToInt($_GET['id']).")");

                            $getdel = db("SELECT id,nick,email,hp FROM ".dba::get('users')." WHERE id = '".convert::ToInt(convert::ToInt($_GET['id']))."'",false,true);

                            Cache::delete('xfire_'.$getdel['user']);

                            db("UPDATE ".dba::get('f_threads')." SET `t_nick` = '".$getdel['nick']."', `t_email` = '".$getdel['email']."', `t_hp` = '".$getdel['hp']."', `t_reg` = '0' WHERE t_reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('f_posts')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('newscomments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('acomments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('dl_comments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('gb_comments')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('gb')." SET `nick` = '".$getdel['nick']."', `email` = '".$getdel['email']."', `hp` = '".$getdel['hp']."', `reg` = '0' WHERE reg = '".$getdel['id']."'");

                            db("DELETE FROM ".dba::get('clicks_ips')." WHERE `uid` = ".$getdel['id']);

                            db("DELETE FROM ".dba::get('acomments')." WHERE von = '".$getdel['id']."' OR an = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('news')." WHERE autor = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('permissions')." WHERE user = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('squaduser')." WHERE user = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('buddys')." WHERE user = '".$getdel['id']."' OR buddy = '".$getdel['id']."'");
                            db("UPDATE ".dba::get('usergb')." SET `reg` = 0 WHERE reg = ".$getdel['id']."");
                            db("DELETE FROM ".dba::get('userpos')." WHERE user = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('userstats')." WHERE user = '".$getdel['id']."'");
                            db("DELETE FROM ".dba::get('rss')." WHERE `userid` = '".$getdel['id']."'");

                            foreach($picformat as $tmpendung)
                            {
                                if(file_exists(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung))
                                    @unlink(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung);

                                if(file_exists(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung))
                                    @unlink(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung);
                            }

                            $qrygl = db("SELECT pic FROM ".dba::get('usergallery')." WHERE user = '".$getdel['id']."'");
                            if(_rows($qrygl) >= 1)
                            {
                                while($getgl = _fetch($qrygl))
                                {
                                    @unlink(basePath."inc/images/uploads/usergallery/".$getdel['id']."_".$getgl['pic']);
                                } //while end

                                db("DELETE FROM ".dba::get('usergallery')." WHERE user = '".$getdel['id']."'");
                            }

                            db("DELETE FROM ".dba::get('users')." WHERE id = '".$getdel['id']."'");
                            $index = info(_user_deleted, "?action=userlist");
                        }
                    }
                break;
                default:
                    $get = db("SELECT id,user,nick,pwd,email,level,position,listck FROM ".dba::get('users')." WHERE id = '".convert::ToInt($_GET['edit'])."'",false,true);
                    $qrysq = db("SELECT id,name FROM ".dba::get('squads')." ORDER BY pos"); $esquads = '';
                    while($getsq = _fetch($qrysq))
                    {
                        $qrypos = db("SELECT id,position FROM ".dba::get('pos')." ORDER BY pid"); $posi = "";
                        while($getpos = _fetch($qrypos))
                        {
                            $sel = db("SELECT id FROM ".dba::get('userpos')."
                            WHERE posi = '".$getpos['id']."'
                            AND squad = '".$getsq['id']."'
                            AND user = '".convert::ToInt($_GET['edit'])."'",true) ? 'selected="selected"' : '';
                            $posi .= show(_select_field_posis, array("value" => $getpos['id'], "sel" => $sel, "what" => string::decode($getpos['position'])));
                        }

                        $check = db("SELECT squad FROM ".dba::get('squaduser')." WHERE user = '".convert::ToInt($_GET['edit'])."' AND squad = '".$getsq['id']."'",true) ? 'checked="checked"' : '';
                        $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => $check, "eposi" => $posi, "squad" => string::decode($getsq['name'])));
                    }

                    $get_identy = show(_admin_user_get_identitat, array("id" => $_GET['edit']));
                    $editpwd = show($dir."/admin_editpwd", array("pwd" => _new_pwd, "epwd" => ""));
                    $index = show($dir."/admin", array(
                                        "enick" => string::decode($get['nick']),
                                        "user" => convert::ToInt($_GET['edit']),
                                        "value" => _button_value_edit,
                                        "eemail" => $get['email'],
                                        "eloginname" => $get['user'],
                                        "esquad" => $esquads,
                                        "editpwd" => $editpwd,
                                        "eposi" => $posi,
                                        "getpermissions" => getPermissions(convert::ToInt($_GET['edit'])),
                                        "getboardpermissions" => getBoardPermissions(convert::ToInt($_GET['edit'])),
                                        "showpos" => getrank($_GET['edit']),
                                        "listck" => (empty($get['listck']) ? '' : ' checked="checked"'),
                                        "alvl" => $get['level'],
                                        "elevel" => (checkme() == 4 || permission("editusers") ? get_level_dropdown_menu($get['level']) : ''),
                                        "get" => $get_identy));
                break;
            }
        }
    }
}