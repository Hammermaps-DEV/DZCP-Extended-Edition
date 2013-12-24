<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._admin_pos;
      $qry = db("SELECT * FROM ".dba::get('pos')." ORDER BY pid"); $color = 0;
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "index=admin&amp;admin=positions&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "index=admin&amp;admin=positions&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => _confirm_del_entry));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        $show_ .= show($dir."/dlkats_show", array("edit" => $edit,
                                                  "name" => string::decode($get['position']),
                                                  "class" => $class,
                                                  "delete" => $delete));
      }

      $show = show($dir."/dlkats", array("head" => _admin_pos,
                                         "show" => $show_,
                                         "add" => _pos_new_head,
                                         "whatkat" => 'positions',
                                         "download" => _admin_download_kat,
                                         "edit" => _editicon_blank,
                                         "delete" => _deleteicon_blank));

      if($_GET['do'] == "edit")
      {
        $qry1 = db("SELECT * FROM ".dba::get('pos')."
                    ORDER BY pid");
        while($get1 = _fetch($qry1))
        {
          $positions .= show(_select_field, array("value" => $get1['pid']+1,
                                                  "what" => _nach.' '.string::decode($get1['position']),
                                                  "sel" => ""));
        }

        $qry = db("SELECT * FROM ".dba::get('pos')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/form_pos", array("newhead" => _pos_edit_head,
                                             "do" => "editpos&amp;id=".convert::ToInt($_GET['id'])."",
                                             "kat" => $get['position'],
                                             "pos" => _position,
                                             "rechte" => _config_positions_rights,
                                             "getpermissions" => getPermissions(convert::ToInt($_GET['id']), 1),
                                             "getboardpermissions" => getBoardPermissions(convert::ToInt($_GET['id']), 1),
                                             "forenrechte" => _config_positions_boardrights,
                                             "positions" => $positions,
                                             "nothing" => _nothing,
                                             "what" => _button_value_edit,
                                             "dlkat" => _admin_download_kat));
      } elseif($_GET['do'] == "editpos") {
        if(empty($_POST['kat']))
        {
          $show = error(_pos_empty_kat);
        } else {
          if($_POST['pos'] == "lazy")
              {
                    $pid = "";
              } else {
                    $pid = ",`pid` = '".convert::ToInt($_POST['pos'])."'";

            if($_POST['pos'] == "1" || "2") $sign = ">= ";
            else $sign = "> ";

            $posi = db("UPDATE ".dba::get('pos')."
                        SET `pid` = pid+1
                        WHERE pid ".$sign." '".convert::ToInt($_POST['pos'])."'");
              }

          $qry = db("UPDATE ".dba::get('pos')."
                     SET `position` = '".string::encode($_POST['kat'])."'
                         ".$pid."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
    // permissions
          db("DELETE FROM ".dba::get('permissions')." WHERE `pos` = '".convert::ToInt($_GET['id'])."'");
          if(!empty($_POST['perm']))
          {
            foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".convert::ToInt($k)."',";
                                  if(!empty($p))$p = ', '.substr($p, 0, strlen($p) - 1);

            db("INSERT INTO ".dba::get('permissions')." SET `pos` = '".convert::ToInt($_GET['id'])."'".$p);
          }
    ////////////////////

    // internal boardpermissions
          db("DELETE FROM ".dba::get('f_access')." WHERE `pos` = '".convert::ToInt($_GET['id'])."'");
          if(!empty($_POST['board']))
          {
            foreach($_POST['board'] AS $v)
              db("INSERT INTO ".dba::get('f_access')." SET `pos` = '".convert::ToInt($_GET['id'])."', `forum` = '".$v."'");
          }
    ////////////////////

          $show = info(_pos_admin_edited, "?index=admin&amp;admin=positions");
        }
      } elseif($_GET['do'] == "delete") {
        db("DELETE FROM ".dba::get('pos')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        db("DELETE FROM ".dba::get('permissions')." WHERE pos = '".convert::ToInt($_GET['id'])."'");

        $show = info(_pos_admin_deleted, "?index=admin&amp;admin=positions");

      } elseif($_GET['do'] == "new") {
        $qry = db("SELECT * FROM ".dba::get('pos')."
                   ORDER BY pid");
        while($get = _fetch($qry))
        {
          $positions .= show(_select_field, array("value" => $get['pid']+1,
                                                            "what" => _nach.' '.string::decode($get['position']),
                                                            "sel" => ""));
        }
        $show = show($dir."/form_pos", array("newhead" => _pos_new_head,
                                             "do" => "add",
                                             "pos" => _position,
                                             "rechte" => _config_positions_rights,
                                             "getpermissions" => getPermissions(),
                                             "getboardpermissions" => getBoardPermissions(),
                                             "nothing" => "",
                                             "forenrechte" => _config_positions_boardrights,
                                             "positions" => $positions,
                                             "kat" => "",
                                             "what" => _button_value_add,
                                             "dlkat" => _admin_download_kat));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['kat']))
        {
          $show = error(_pos_empty_kat);
        } else {
          if($_POST['pos'] == "1" || "2") $sign = ">= ";
          else $sign = "> ";

          db("UPDATE ".dba::get('pos')." SET `pid` = pid+1 WHERE pid ".$sign." '".convert::ToInt($_POST['pos'])."'");
          db("INSERT INTO ".dba::get('pos')." SET `pid` = '".convert::ToInt($_POST['pos'])."', `position`  = '".string::encode($_POST['kat'])."'");
          $posID = database::get_insert_id();
    // permissions
          foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".convert::ToInt($k)."',";
                                if(!empty($p))$p = ', '.substr($p, 0, strlen($p) - 1);

          db("INSERT INTO ".dba::get('permissions')." SET `pos` = '".$posID."'".$p);
    ////////////////////

    // internal boardpermissions
          if(!empty($_POST['board']))
          {
            foreach($_POST['board'] AS $v)
              db("INSERT INTO ".dba::get('f_access')." SET `pos` = '".$posID."', `forum` = '".$v."'");
          }
    ////////////////////

          $show = info(_pos_admin_added, "?index=admin&amp;admin=positions");
        }
      }