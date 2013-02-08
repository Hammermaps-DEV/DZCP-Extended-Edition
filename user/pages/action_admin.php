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
    if($chkMe == "unlogged")
        $index = error(_error_have_to_be_logged, 1);
    else
    {
        if(isset($_GET['edit']) && data(convert::ToInt($_GET['edit']), "level") == 4 && $userid != $rootAdmin)
            $index = error(_error_edit_admin, 1);
        else if(isset($_GET['edit']) && convert::ToInt($_GET['edit']) == $userid)
        {
            $qrysq = db("SELECT id,name FROM ".$db['squads']." ORDER BY pos"); $esquads = '';
            while($getsq = _fetch($qrysq))
            {
                $qrypos = db("SELECT id,position FROM ".$db['pos']." ORDER BY pid"); $posi = '';
                while($getpos = _fetch($qrypos))
                {
                    $sel = (db("SELECT id FROM ".$db['userpos']." WHERE posi = '".$getpos['id']."' AND squad = '".$getsq['id']."' AND user = '".convert::ToInt($_GET['edit'])."'",true) ? 'selected="selected"' : '');
                    $posi .= show(_select_field_posis, array("value" => $getpos['id'], "sel" => $sel, "what" => re($getpos['position'])));
                }

                $check = (db("SELECT squad FROM ".$db['squaduser']." WHERE user = '".intval($_GET['edit'])."' AND squad = '".$getsq['id']."'",true) ? 'checked="checked"' : '');
                $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => $check, "eposi" => $posi, "squad" => re($getsq['name'])));
                unset($posi,$check);
            }

            $index = show($dir."/admin_self", array("showpos" => getrank($_GET['edit']), "esquad" => $esquads, "value" => _button_value_edit));
        }
        else
        {
            switch($do)
            {
                case 'identy':
                    if((data(convert::ToInt($_GET['id']), "level") == 4 && $userid != $rootAdmin) || convert::ToInt($_GET['id']) == $rootAdmin)
                        $index = error(_identy_admin, 1);
                    else
                    {
                        $msg = show(_admin_user_get_identy, array("nick" => autor($_GET['id'])));
                        $index = info($msg, "?action=user&amp;id=".convert::ToInt($_GET['id'])."");

                        ## Ereignis in den Adminlog schreiben ##
                        wire_ipcheck("ident(".$userid."_".convert::ToInt($_GET['id']).")");

                        //User abmelden
                        logout();

                        ## User aus der Datenbank suchen ##
                        if(!empty($_GET['id']))
                        {
                            $sql = db("SELECT id,pwd,time FROM ".$db['users']." WHERE id = '".convert::ToInt($_GET['id'])."' AND level != '0'");
                            if(_rows($sql))
                            {
                                $get = _fetch($sql);

                                ## Schreibe Werte in die Server Sessions ##
                                $_SESSION['id']         = $get['id'];
                                $_SESSION['pwd']        = $get['pwd'];
                                $_SESSION['lastvisit']  = $get['time'];
                                $_SESSION['ip']         = visitorIp();

                                ## Aktualisiere Datenbank ##
                                db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".visitorIp()."' WHERE id = '".$get['id']."'");
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

                        $qry_fields = db("SHOW FIELDS FROM ".$db['permissions']); $sql_update = '';
                        while($get = _fetch($qry_fields))
                        {
                            if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum')
                            {
                                $sql = array_key_exists('p_'.$get['Field'], $_POST['perm']) ? $get['Field'].' = 1' : $get['Field'].' = 0';
                                $sql_update .= $sql.', ';
                            }
                        }

                        db('UPDATE '.$db['permissions'].' SET '.substr($sql_update, 0, -2).' WHERE user = '.$edituser);

                        // Internal Boardpermissions Update
                        if(empty($_POST['board']))
                            $_POST['board'] = array();

                        //Cleanup
                        $sql = db('SELECT id,forum FROM `'.$db['f_access'].'` WHERE `user` = '.$edituser);
                        while($get = _fetch($sql))
                        { if(!array_var_exists($get['forum'],$_POST['board'])) db('DELETE FROM `'.$db['f_access'].'` WHERE `id` = '.$get['id']); }

                        //Neuer Eintrag
                        if(count($_POST['board']) >= 1)
                        {
                            foreach($_POST['board'] AS $boardpem)
                            { if(!db("SELECT * FROM `".$db['f_access']."` WHERE `user` = ".$edituser." AND `forum` = ".$boardpem,true)) db("INSERT INTO ".$db['f_access']." SET `user` = '".$edituser."', `forum` = '".$boardpem."'"); }
                        }

                        //intforum gegen f_access tabelle tauschen *intforum entfernen
                        //db("UPDATE `".$db['permissions']."` SET `intforum` = '".(!count($_POST['board']) ? '0' : '1')."' WHERE `id` =".$edituser);

                        // Squads Update
                        db("DELETE FROM ".$db['squaduser']." WHERE user = '".$edituser."'");
                        db("DELETE FROM ".$db['userpos']." WHERE user = '".$edituser."'");

                        $sq = db("SELECT id FROM ".$db['squads']."");
                        while($getsq = _fetch($sq))
                        {
                            if(isset($_POST['squad'.$getsq['id']]))
                               db("INSERT INTO ".$db['squaduser']." SET `user` = '".$edituser."', `squad`  = '".convert::ToInt($_POST['squad'.$getsq['id']])."'");

                            if(isset($_POST['squad'.$getsq['id']]))
                                db("INSERT INTO ".$db['userpos']." SET `user` = '".$edituser."', `posi`   = '".convert::ToInt($_POST['sqpos'.$getsq['id']])."', `squad`  = '".convert::ToInt($getsq['id'])."'");
                        }

                        if(isset($_POST['passwd']) && !empty($_POST['passwd']))
                        {
                            $default_pwd_encoder = settings('default_pwd_encoder');
                            if($_POST['passwd']) $newpwd = "`pwd` = '".pass_hash($_POST['passwd'],$default_pwd_encoder)."', `pwd_encoder` = ".convert::ToInt($default_pwd_encoder).",";
                        }

                        $level_sql = ($_POST['level'] == 4 ? (data($userid, "level") == 4 || $userid == $rootAdmin ? ", `level`  = '".convert::ToInt($_POST['level'])."' " : '') : ", `level`  = '".convert::ToInt($_POST['level'])."' ");
                        db("UPDATE ".$db['users']." SET ".(isset($_POST['passwd']) && !empty($_POST['passwd']) ? $newpwd : '')."
                           `nick`   = '".convert::ToString(up($_POST['nick']))."',
                           `email`  = '".convert::ToString($_POST['email'])."',
                           `user`   = '".convert::ToString($_POST['loginname'])."',
                           `listck` = '".convert::ToInt(isset($_POST['listck']) ? $_POST['listck'] : 0)."'
                           ".$level_sql."
                          WHERE id = ".$edituser);

                        ## Ereignis in den Adminlog schreiben ##
                        wire_ipcheck("upduser(".$userid."_".$edituser.")");
                    }

                    $index = info(_admin_user_edited, "?action=admin&amp;edit=".$edituser);
                break;
                case 'updateme':
                    // Squads Update
                    db("DELETE FROM ".$db['squaduser']." WHERE user = '".$userid."'");
                    db("DELETE FROM ".$db['userpos']." WHERE user = '".$userid."'");

                    $sq = db("SELECT id FROM ".$db['squads']."");
                    while($getsq = _fetch($sq))
                    {
                        if(isset($_POST['squad'.$getsq['id']]))
                            db("INSERT INTO ".$db['squaduser']." SET `user` = '".$userid."', `squad`  = '".convert::ToInt($_POST['squad'.$getsq['id']])."'");

                        if(isset($_POST['squad'.$getsq['id']]))
                            db("INSERT INTO ".$db['userpos']." SET `user` = '".$userid."', `posi`   = '".convert::ToInt($_POST['sqpos'.$getsq['id']])."', `squad`  = '".convert::ToInt($getsq['id'])."'");
                    }

                    $index = info(_admin_user_edited, "?action=admin&amp;edit=".$userid."");
                break;
                case 'delete':
                    $index = show(_user_delete_verify, array("user" => autor(convert::ToInt($_GET['id'])), "id" => convert::ToInt($_GET['id'])));

                    if(isset($_GET['verify']) && $_GET['verify'] == "yes" && ($userid == convert::ToInt($_GET['id']) || data($userid, "level") == 4))
                    {
                        if((data(convert::ToInt($_GET['id']), "level") == 4 || data(convert::ToInt($_GET['id']), "level") == 3) && $userid != $rootAdmin)
                            $index = error(_user_cant_delete_admin, 2);
                        else if (convert::ToInt($_GET['id']) == $rootAdmin)
                            $index = error(_user_cant_delete_radmin, 2);
                        else
                        {
                            ## Ereignis in den Adminlog schreiben ##
                            wire_ipcheck("deluser(".$userid."_".convert::ToInt($_GET['id']).")");

                            db("UPDATE ".$db['f_posts']." SET `reg` = 0 WHERE reg = ".convert::ToInt($_GET['id'])."");
                            db("UPDATE ".$db['f_threads']." SET `t_reg` = 0 WHERE t_reg = ".convert::ToInt($_GET['id'])."");
                            db("UPDATE ".$db['gb']." SET `reg` = 0 WHERE reg = ".convert::ToInt($_GET['id'])."");
                            db("UPDATE ".$db['newscomments']." SET `reg` = 0 WHERE reg = ".convert::ToInt($_GET['id'])."");
                            db("UPDATE ".$db['usergb']." SET `reg` = 0 WHERE reg = ".convert::ToInt($_GET['id'])."");

                            db("DELETE FROM ".$db['msg']." WHERE von = '".convert::ToInt($_GET['id'])."' OR an = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['news']." WHERE autor = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['permissions']." WHERE user = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['f_access']." WHERE user = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['squaduser']." WHERE user = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['taktik']." WHERE autor = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['buddys']." WHERE user = '".convert::ToInt($_GET['id'])."' OR buddy = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['userpos']." WHERE user = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['users']." WHERE id = '".convert::ToInt($_GET['id'])."'");
                            db("DELETE FROM ".$db['userstats']." WHERE user = '".convert::ToInt($_GET['id'])."'");

                            $index = info(_user_deleted, "?action=userlist");
                        }
                    }
                break;
                default:
                    $qry = db("SELECT id,user,nick,pwd,email,level,position,listck FROM ".$db['users']." WHERE id = '".convert::ToInt($_GET['edit'])."'");
                    while($get = _fetch($qry))
                    {
                        $qrysq = db("SELECT id,name FROM ".$db['squads']." ORDER BY pos"); $esquads = '';
                        while($getsq = _fetch($qrysq))
                        {
                            $qrypos = db("SELECT id,position FROM ".$db['pos']." ORDER BY pid"); $posi = "";
                            while($getpos = _fetch($qrypos))
                            {
                                $sel = db("SELECT id FROM ".$db['userpos']."
                                WHERE posi = '".$getpos['id']."'
                                AND squad = '".$getsq['id']."'
                                AND user = '".intval($_GET['edit'])."'",true) ? 'selected="selected"' : '';
                                $posi .= show(_select_field_posis, array("value" => $getpos['id'], "sel" => $sel, "what" => re($getpos['position'])));
                            }

                            $check = db("SELECT squad FROM ".$db['squaduser']." WHERE user = '".intval($_GET['edit'])."' AND squad = '".$getsq['id']."'",true) ? 'checked="checked"' : '';
                            $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => $check, "eposi" => $posi, "squad" => re($getsq['name'])));
                        }

                        $get_identy = show(_admin_user_get_identitat, array("id" => $_GET['edit']));
                        $editpwd = show($dir."/admin_editpwd", array("pwd" => _new_pwd, "epwd" => ""));
                        $index = show($dir."/admin", array(
                                "enick" => re($get['nick']),
                                "user" => intval($_GET['edit']),
                                "value" => _button_value_edit,
                                "eemail" => $get['email'],
                                "eloginname" => $get['user'],
                                "esquad" => $esquads,
                                "editpwd" => $editpwd,
                                "eposi" => $posi,
                                "getpermissions" => getPermissions(intval($_GET['edit'])),
                                "getboardpermissions" => getBoardPermissions(intval($_GET['edit'])),
                                "showpos" => getrank($_GET['edit']),
                                "listck" => (empty($get['listck']) ? '' : ' checked="checked"'),
                                "alvl" => $get['level'],
                                "elevel" => ($chkMe == 4 || permission("editusers") ? get_level_dropdown_menu($get['level']) : ''),
                                "get" => $get_identy));
                    }
                break;
            }
        }
    }
}
?>