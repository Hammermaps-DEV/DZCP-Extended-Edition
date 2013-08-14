<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._member_admin_header;
      $qry = db("SELECT * FROM ".dba::get('squads')." ORDER BY pos");
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=squads&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=squads&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => _confirm_del_team));
        $icon = show(_gameicon, array("icon" => $get['icon']));

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $squads .= show($dir."/squads_show", array("squad" => '<a href="../squads/?action=shows&amp;id='.$get['id'].'" style="display:block">'.string::decode($get['name']).'</a>',
                                                   "game" => string::decode($get['game']),
                                                   "icon" => $icon,
                                                   "edit" => $edit,
                                                   "class" => $class,
                                                   "delete" => $delete));
      }

      $show = show($dir."/squads", array("memberadminheader" => _member_admin_header,
                                         "squad" => _member_admin_squad,
                                         "game" => _member_admin_game,
                                         "delete" => _deleteicon_blank,
                                         "edit" => _editicon_blank,
                                         "add" => _member_admin_add_header,
                                         "squads" => $squads));
      if($_GET['do'] == "add")
      {
        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                           ORDER BY s1.name, s2.pos");
        while($getnav = _fetch($qrynav))
        {
          if($thiskat != $getnav['kat']) {
            $navigation .= '
              <option class="dropdownKat" value="lazy">'.string::decode($getnav['katname']).'</option>
              <option value="'.string::decode($getnav['placeholder']).'-1">-> '._admin_first.'</option>
            ';
          }
          $thiskat = $getnav['kat'];

          $navigation .= empty($getnav['name']) ? '' : '<option value="'.string::decode($getnav['placeholder']).'-'.($getnav['pos']+1).'">'._nach.' -> '.navi_name(string::decode($getnav['name'])).'</option>';
        }

        $qry = db("SELECT * FROM ".dba::get('squads')." ORDER BY pos");
        while($get = _fetch($qry))
        {
          $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                  "sel" => "",
                                                  "what" => _nach.' '.string::decode($get['name'])));
        }

        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,array('gif','jpg','png'));
        foreach($files as $file)
        {
            $gameicons .= show(_select_field, array("value" => $file,
                                                    "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)),
                                                    "sel" => ""));
        }

        $show = show($dir."/squads_add", array("memberadminaddheader" => _member_admin_add_header,
                                               "squad" => _member_admin_squad,
                                               "pos" => _position,
                                               "value" => _button_value_add,
                                               "icon" => _member_admin_icon,
                                               "info" => _admin_squad_show_info,
                                               "status" => _status,
                                               "aktiv"  => _sq_aktiv,
                                               "inaktiv" => _sq_inaktiv,
                                               "logo" => _team_logo,
                                               "banner" => _sq_banner,
                                               "desc" => _dl_besch,
                                               "sstatus" => _sq_sstatus,
                                               "cstatus" => "",
                                               "navi" => _admin_squads_nav,
                                               "first" => _admin_first,
                                               "show" => _show,
                                               "dontshow" => _dont_show,
                                               "upload" => _member_admin_icon_upload,
                                               "gameicons" => $gameicons,
                                               "positions" => $positions,
                                               "check_show" => _button_value_show,
                                               "roster" => _admin_sqauds_roster,
                                                                                             "navigation" => $navigation,
                                                                                             "nav_info" => _admin_squads_nav_info,
                                                                                             "no_navi" => _admin_squads_no_navi,
                                                                                             "teams" => _admin_squads_teams,
                                                                                             "game" => _member_admin_game));

      } elseif($_GET['do'] == "addsquad") {
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
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('squads')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $pos = db("SELECT pos,name FROM ".dba::get('squads')." ORDER BY pos");
        while($getpos = _fetch($pos))
        {
          if($getpos['name'] != $get['name'])
          {
            $mpos = db("SELECT pos FROM ".dba::get('squads')."
                        WHERE id != '".convert::ToInt($get['id'])."'
                        AND pos = '".convert::ToInt(($get['pos']-1))."'");
            $mp = _fetch($mpos);

            $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                    "what" => _nach.' '.string::decode($getpos['name']),
                                                    "sel" => ($getpos['pos'] == $mp['pos'] ? 'selected="selected"' : '')));
          }
        }

        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                           ORDER BY s1.name, s2.pos");
        $i = 1;
        $thiskat = '';
        while($getnav = _fetch($qrynav))
        {
          if($thiskat != $getnav['kat']) {
            $navigation .= '
              <option class="dropdownKat" value="lazy">'.string::decode($getnav['katname']).'</option>
              <option value="'.string::decode($getnav['placeholder']).'-1">-> '._admin_first.'</option>
            ';
          }
          $thiskat = $getnav['kat'];
          $sel[$i] = ($getnav['url'] == '../squads/?action=shows&amp;id='.convert::ToInt($_GET['id'])) ? 'selected="selected"' : '';

          $navigation .= empty($getnav['name']) ? '' : '<option value="'.string::decode($getnav['placeholder']).'-'.($getnav['pos']+1).'" '.$sel[$i].'>'._nach.' -> '.navi_name(string::decode($getnav['name'])).'</option>';

          $i++;
        }

        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true);
        foreach($files as $file)
        {
          if(preg_match("#\.gif|.jpg|.png#Uis",$file))
            $gameicons .= show(_select_field, array("value" => $file,
                                                    "sel" => ($file == $get['icon'] ? 'selected="selected"' : ''),
                                                    "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file))));
        }

        foreach($picformat AS $end)
        {
          if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end))
          {
            $image = '<img src="../inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end.'" width="200" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=../inc/images/squads/'.convert::ToInt($_GET['id']).'.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
            break;
          }
        }

        foreach($picformat AS $end)
        {
          if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end))
          {
            $logoimage = '<img src="../inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end.'" height="60" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=../inc/images/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
            break;
          }
        }

        $show = show($dir."/squads_edit", array("memberadminaddheader" => _member_admin_edit_header,
                                                "squad" => _member_admin_squad,
                                                "id" => convert::ToInt($_GET['id']),
                                                "pos" => _position,
                                                "icon" => _member_admin_icon,
                                                "gameicons" => $gameicons,
                                                "logo" => _team_logo,
                                                "value" => _button_value_edit,
                                                "status" => _status,
                                                "aktiv"  => _sq_aktiv,
                                                "inaktiv" => _sq_inaktiv,
                                                "sstatus" => _sq_sstatus,
                                                "banner" => _sq_banner,
                                                "image" => $image,
                                                "logoimage" => $logoimage,
                                                "desc" => _dl_besch,
                                                "beschreibung" => string::decode($get['beschreibung']),
                                                "cstatus" => ($get['status'] ? 'selected="selected"' : ''),
                                                "first" => _admin_first,
                                                "info" => _admin_squad_show_info,
                                                "navi" => _admin_squads_nav,
                                                "upload" => _member_admin_icon_upload,
                                                "sshown" => ($get['shown'] ? 'selected="selected"' : ''),
                                                "nothing" => _nothing,
                                                "selr" => ($get['navi'] ? 'selected="selected"' : ''),
                                                                                                "selt" => ($get['team_show'] ? 'selected="selected"' : ''),
                                                                                                "navigation" => $navigation,
                                                                                                "roster" => _admin_sqauds_roster,
                                                                                              "navigation" => $navigation,
                                                                                              "nav_info" => _admin_squads_nav_info,
                                                                                                "no_navi" => _admin_squads_no_navi,
                                                                                              "teams" => _admin_squads_teams,
                                                                                              "show" => _show,
                                                "dontshow" => _dont_show,
                                                "ssquad" => string::decode($get['name']),
                                                "sgame" => string::decode($get['game']),
                                                "positions" => $positions,
                                                "check_show" => _button_value_show,
                                                "game" => _member_admin_game));
      } elseif($_GET['do'] == "editsquad") {
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
      } elseif($_GET['do'] == "delete") {
        db("DELETE FROM ".dba::get('squads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
                db("DELETE FROM ".dba::get('navi')." WHERE url = '../squads/?action=shows&amp;id=".convert::ToInt($_GET['id'])."'");
        $show = info(_admin_squad_deleted, "?admin=squads");
      }