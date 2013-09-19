<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        if(empty($_POST['squad']))
        {
          $show = error(_admin_squad_no_squad);
        } elseif(empty($_POST['game']))
        {
          $show = error(_admin_squad_no_game);
        } else {
          $ask = db("SELECT pos FROM ".dba::get('squads')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
          $get = _fetch($ask);

          if($_POST['position'] != $get['pos'])
          {
            if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
            else $sign = "> ";

            $posi = db("UPDATE ".dba::get('squads')."
                        SET `pos` = pos+1
                        WHERE pos ".$sign." '".convert::ToInt($_POST['position'])."'");
          }

              if($_POST['position'] == "lazy") $newpos = "";
              else $newpos = "`pos` = '".convert::ToInt($_POST['position'])."',";
              if($_POST['icon'] == "lazy") $newicon = "";
              else $newicon = "`icon` = '".string::encode($_POST['icon'])."',";

          $qry = db("UPDATE ".dba::get('squads')."
                     SET `name`         = '".string::encode($_POST['squad'])."',
                         `game`         = '".string::encode($_POST['game'])."',
                         ".$newpos."
                         ".$newicon."
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                         `shown`        = '".convert::ToInt($_POST['show'])."',
                         `navi`         = '".convert::ToInt($_POST['roster'])."',
                         `team_show`	= '".convert::ToInt($_POST['team_show'])."',
                         `status`       = '".convert::ToInt($_POST['status'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

               if($_POST['navi'] != "lazy")
                    {
                        $qry = db("SELECT * FROM ".dba::get('navi')." WHERE url = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."'");
                $get = _fetch($qry);
                        if(_rows($qry))
                    {
                            if($_POST['navi'] == "1" || "2") $sign = ">= ";
                            else $sign = "> ";

                            $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                            $pos = preg_replace("=nav_(.+)-=","",$_POST['navi']);

                            $posi = db("UPDATE ".dba::get('navi')."
                                                    SET pos = pos+1
                                                    WHERE pos ".$sign." '".convert::ToInt($pos)."'");

                            $posi = db("UPDATE ".dba::get('navi')."
                                                    SET `pos`       = '".convert::ToInt($pos)."',
                                                            `kat`       = '".string::encode($kat)."',
                                                            `name`      = '".string::encode($_POST['squad'])."',
                                                            `url`       = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."'
                                                    WHERE id = '".convert::ToInt($get['id'])."'");
                        } else {
                            if($_POST['navi'] == "1" || "2") $signnav = ">= ";
                            else $signnav = "> ";

                            $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                            $pos = preg_replace("=nav_(.*?)-=","",$_POST['navi']);

                            db("UPDATE ".dba::get('navi')." SET `pos` = pos+1 WHERE pos ".$signnav." '".convert::ToInt($pos)."'");

                            db("INSERT INTO ".dba::get('navi')."
                                    SET `pos`       = '".convert::ToInt($pos)."',
                                            `kat`       = '".string::encode($kat)."',
                                            `name`      = '".string::encode($_POST['squad'])."',
                                            `url`       = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."',
                                            `shown`     = '1',
                                            `type`      = '2'");
                        }
                    } else {
                        $qry = db("SELECT * FROM ".dba::get('navi')." WHERE url = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."'");
                        if(_rows($qry))	db("DELETE FROM ".dba::get('navi')." WHERE url = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."'");
                    }

          $tmp = $_FILES['banner']['tmp_name'];
          $type = $_FILES['banner']['type'];
          $end = explode(".", $_FILES['banner']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            foreach($picformat AS $end1)
            {
              $img = getimagesize($tmp);
                            if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end1))
              {
                @unlink(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end1);
                break;
              }
            }
            if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
            {
              copy($tmp, basePath."/inc/images/uploads/squads/".convert::ToInt($_GET['id']).".".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            $img = getimagesize($tmp);
                        foreach($picformat AS $end1)
            {
              if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end1))
              {
                @unlink(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end1);
                break;
              }
            }
            if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
            {
              @copy($tmp, basePath."/inc/images/uploads/squads/".convert::ToInt($_GET['id'])."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $show = info(_admin_squad_edit_successful, "?admin=squads");
        }