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

################
## Userbuddys ##
################
if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $where = _site_user_buddys;
    if(checkme() == "unlogged")
        $index = error(_error_have_to_be_logged);
    else
    {
        switch($do)
        {
            case 'add':
                if($_POST['users'] == "-")
                    $index = error(_error_select_buddy);
                else if($_POST['users'] == userid())
                    $index = error(_error_buddy_self);
                else if(!check_buddy($_POST['users']))
                    $index = error(_error_buddy_already_in);
                else
                {
                    db("INSERT INTO ".dba::get('buddys')." SET `user` = '".userid()."', `buddy` = '".convert::ToInt($_POST['users'])."'");
                    $msg = show(_buddy_added_msg, array("user" => autor()));
                    db("INSERT INTO ".dba::get('msg')."
                        SET `datum`     = '".time()."',
                            `von`       = '0',
                            `an`        = '".convert::ToInt($_POST['users'])."',
                            `titel`     = '".string::encode(_buddy_title)."',
                            `nachricht` = '".string::encode($msg)."'");

                    $index = info(_add_buddy_successful, "?action=buddys");
                }
            break;
            case 'addbuddy':
                $user = (isset($_GET['id']) ? $_GET['id'] : $_POST['users']);

                if($user == "-")
                    $index = error(_error_select_buddy);
                elseif($user == userid())
                    $index = error(_error_buddy_self);
                 elseif(!check_buddy($user))
                    $index = error(_error_buddy_already_in);
                else
                {
                    db("INSERT INTO ".dba::get('buddys')." SET `user`   = '".userid()."', `buddy`  = '".convert::ToInt($user)."'");
                    $msg = show(_buddy_added_msg, array("user" => addslashes(autor())));
                    db("INSERT INTO ".dba::get('msg')."
                        SET `datum`     = '".time()."',
                            `von`       = '0',
                            `an`        = '".convert::ToInt($user)."',
                            `titel`     = '".string::encode(_buddy_title)."',
                            `nachricht` = '".string::encode($msg)."'");

                    $index = info(_add_buddy_successful, "?action=buddys");
                }
            break;
            case 'delete':
                db("DELETE FROM ".dba::get('buddys')." WHERE buddy = ".convert::ToInt($_GET['id'])." AND user = '".userid()."'");
                $msg = show(_buddy_del_msg, array("user" => addslashes(autor())));
                db("INSERT INTO ".dba::get('msg')."
                      SET `datum`     = '".time()."',
                          `von`       = '0',
                          `an`        = '".convert::ToInt($_GET['id'])."',
                          `titel`     = '".string::encode(_buddy_title)."',
                          `nachricht` = '".string::encode($msg)."'");

                $index = info(_buddys_delete_successful, "../user/?action=buddys");
            break;
            default: //default page
                $qry = db("SELECT * FROM ".dba::get('buddys')." WHERE user = ".userid());
                $too = ''; $color = 1; $buddys = '';

                if(_rows($qry) >= 1)
                {
                    while($get = _fetch($qry))
                    {
                        $pn = show(_pn_write, array("id" => $get['buddy'], "nick" => data($get['buddy'], "nick")));
                        $delete = show(_buddys_delete, array("id" => $get['buddy']));
                        $yesnocheck = db("SELECT * FROM ".dba::get('buddys')." where user = '".$get['buddy']."' AND buddy = '".userid()."'");
                        $too = (_rows($yesnocheck) ? _buddys_yesicon : _buddys_noicon);
                        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                        $buddys .= show($dir."/buddys_show", array("nick" => autor($get['buddy']),
                                                                   "onoff" => onlinecheck($get['buddy']),
                                                                   "pn" => $pn,
                                                                   "class" => $class,
                                                                   "too" => $too,
                                                                   "delete" => $delete));
                    } //while end
                }

                if(empty($buddys))
                    $buddys = show(_no_entrys_yet_all, array("colspan" => "1"));

                $qry = db("SELECT id,nick FROM ".dba::get('users')." WHERE level != 0 ORDER BY nick"); $users = '';
                if(_rows($qry) >= 1)
                {
                    while($get = _fetch($qry))
                    {
                        $users .= show(_to_users, array("id" => $get['id'], "nick" => data($get['id'], "nick")));
                    } //while end
                }

                $add = show("".$dir."/buddys_add", array("users" => $users, "value" => _button_value_addto));
                $index = show($dir."/buddys", array("too" => _yesno, "show" => $buddys, "add" => $add));
            break;
        }
    }
}