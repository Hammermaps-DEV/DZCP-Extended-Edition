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
          if($_POST['position'] == 1 || $_POST['position'] == 2) $sign = ">= ";
          else $sign = "> ";

          db("UPDATE ".dba::get('squads')." SET `pos` = pos+1 WHERE pos ".$sign." '".convert::ToInt($_POST['position'])."'");
          $qry = db("INSERT INTO ".dba::get('squads')."
                     SET `name`         = '".string::encode($_POST['squad'])."',
                         `game`         = '".string::encode($_POST['game'])."',
                         `icon`         = '".string::encode($_POST['icon'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                         `shown`        = '".convert::ToInt($_POST['show'])."',
                         `navi`       	= '".convert::ToInt($_POST['roster'])."',
                         `team_show`    = '".convert::ToInt($_POST['team_show'])."',
                         `status`       = '".convert::ToInt($_POST['status'])."',
                         `pos`          = '".convert::ToInt($_POST['position'])."'");

                    $insert_id = database::get_insert_id();

                    if($_POST['navi'] != "lazy") {
                        if($_POST['navi'] == "1" || "2") $signnav = ">= ";
                        else $signnav = "> ";

                        $kat = preg_replace('/-(\d+)/','',$_POST['navi']);
                        $pos = preg_replace("=nav_(.*?)-=","",$_POST['navi']);

                        db("UPDATE ".dba::get('navi')." SET `pos` = pos+1 WHERE pos ".$signnav." '".convert::ToInt($pos)."'");

                        db("INSERT INTO ".dba::get('navi')."
                                SET `pos`       = '".convert::ToInt($pos)."',
                                        `kat`       = '".string::encode($kat)."',
                                        `name`      = '".string::encode($_POST['squad'])."',
                                        `url`       = '../squads/?action=shows&amp;id=".$insert_id."',
                                        `shown`     = '1',
                                        `type`      = '2'");
                    }

          $tmp = $_FILES['banner']['tmp_name'];
          $type = $_FILES['banner']['type'];
          $end = explode(".", $_FILES['banner']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            $img = getimagesize($tmp);
                        if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])
            {
              @copy($tmp, basePath."/inc/images/uploads/squads/".$insert_id.".".strtolower($end));
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
                        if($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || !$img[0])
            {
              @copy($tmp, basePath."/inc/images/uploads/squads/".$insert_id."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $show = info(_admin_squad_add_successful, "?admin=squads");
        }