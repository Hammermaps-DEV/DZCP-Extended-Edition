<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._editor_head;
wysiwyg::set('advanced');
      if($_GET['do'] == "add")
      {
        $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                   ORDER BY s1.name, s2.pos");
         $thiskat = '';
        while($get = _fetch($qry))
        {
          if($thiskat != $get['kat']) {
            $position .= '
              <option class="dropdownKat" value="lazy">'.string::decode($get['katname']).'</option>
              <option value="'.string::decode($get['placeholder']).'-1">-> '._admin_first.'</option>
            ';
          }
          $thiskat = $get['kat'];
          $sel = ($get['editor'] == $_GET['id']) ? 'selected="selected"' : '';

          $position .= empty($get['name']) ? '' : '<option value="'.string::decode($get['placeholder']).'-'.($get['pos']+1).'" '.$sel.'>'._nach.' -> '.navi_name(string::decode($get['name'])).'</option>';
        }

        $show = show($dir."/form_editor", array("head" => _editor_add_head,
                                                "what" => _button_value_add,
                                                "bbcode" => _bbcode,
                                                "titel" => _titel,
                                                "preview" => _preview,
                                                "e_titel" => "",
                                                "e_inhalt" => "",
                                                "checked" => "",
                                                "pos" => _position,
                                                "name" => _editor_linkname,
                                                "n_name" => "",
                                                "position" => $position,
                                                "ja" => _yes,
                                                "nein" => _no,
                                                "wichtig" => _navi_wichtig,
                                                "error" => "",
                                                "allow_html" => _editor_allow_html,
                                                "inhalt" => _inhalt,
                                                "do" => "addsite"));
      } elseif($_GET['do'] == "addsite") {
        if(empty($_POST['titel']) || empty($_POST['inhalt']) || $_POST['pos'] == "lazy")
        {
          if(empty($_POST['titel'])) $error = _empty_titel;
          elseif(empty($_POST['inhalt'])) $error = _empty_editor_inhalt;
          elseif($_POST['pos'] == "lazy") $error = _navi_no_pos;

          $error = show("errors/errortable", array("error" => $error));

          if(isset($_POST['html'])) $checked = "checked=\"checked\"";

          $kat_ = preg_replace('/-(\d+)/','',$_POST['pos']);
          $pos_ = preg_replace("=nav_(.*?)-=","",$_POST['pos']);

          $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                     ORDER BY s1.name, s2.pos");
          $thiskat = '';
          while($get = _fetch($qry))
          {
            if($thiskat != $get['kat']) {
              $position .= '
                <option class="dropdownKat" value="lazy">'.string::decode($get['katname']).'</option>
                <option value="'.string::decode($get['placeholder']).'-1">-> '._admin_first.'</option>
              ';
            }

            $thiskat = $get['kat'];
            $sel = ($get['kat'] == $kat_ && ($get['pos']+1) == $pos_) ? 'selected="selected"' : '';

            $position .= empty($get['name']) ? '' : '<option value="'.string::decode($get['placeholder']).'-'.($get['pos']+1).'" '.$sel.'>'._nach.' -> '.navi_name(string::decode($get['name'])).'</option>';
          }

          $show = show($dir."/form_editor", array("head" => _editor_add_head,
                                                  "what" => _button_value_add,
                                                  "preview" => _preview,
                                                  "bbcode" => _bbcode,
                                                  "error" => $error,
                                                  "checked" => $checked,
                                                  "pos" => _position,
                                                  "ja" => _yes,
                                                  "nein" => _no,
                                                  "name" => _editor_linkname,
                                                  "position" => $position,
                                                  "n_name" => string::decode($_POST['name']),
                                                  "wichtig" => _navi_wichtig,
                                                  "titel" => _titel,
                                                  "e_titel" => string::decode($_POST['titel']),
                                                  "e_inhalt" => string::decode($_POST['inhalt']),
                                                  "allow_html" => _editor_allow_html,
                                                  "inhalt" => _inhalt,
                                                  "do" => "addsite"));
        } else {
          $qry = db("INSERT INTO ".dba::get('sites')."
                     SET `titel` = '".string::encode($_POST['titel'])."',
                         `text`  = '".string::encode($_POST['inhalt'])."',
                         `html`  = '".convert::ToInt($_POST['html'])."'");
          $insert_id = database::get_insert_id();

          if($_POST['pos'] == "1" || "2") $sign = ">= ";
          else $sign = "> ";

          $kat = preg_replace('/-(\d+)/','',$_POST['pos']);
          $pos = preg_replace("=nav_(.*?)-=","",$_POST['pos']);

          $url = "../sites/?show=".$insert_id."";

          $posi = db("UPDATE ".dba::get('navi')."
                      SET `pos` = pos+1
                      WHERE pos ".$sign." '".convert::ToInt($pos)."'");

          $posi = db("INSERT INTO ".dba::get('navi')."
                      SET `pos`     = '".convert::ToInt($pos)."',
                          `kat`     = '".string::encode($kat)."',
                          `name`    = '".string::encode($_POST['name'])."',
                          `url`     = '".string::encode($url)."',
                          `shown`   = '1',
                          `type`    = '3',
                          `editor`  = '".convert::ToInt($insert_id)."',
                          `wichtig` = '0'");

          $show = info(_site_added, "?admin=editor");
        }
      } elseif($_GET['do'] == "edit") {
        $qrys = db("SELECT * FROM ".dba::get('sites')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'");
        $gets = _fetch($qrys);

        $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                   ORDER BY s1.name, s2.pos");
        $thiskat = '';
        while($get = _fetch($qry))
        {
          if($thiskat != $get['kat']) {
            $position .= '
              <option class="dropdownKat" value="lazy">'.string::decode($get['katname']).'</option>
              <option value="'.string::decode($get['placeholder']).'-1">-> '._admin_first.'</option>
            ';
          }
          $thiskat = $get['kat'];
          $sel = ($get['editor'] == $_GET['id']) ? 'selected="selected"' : '';

          $position .= empty($get['name']) ? '' : '<option value="'.string::decode($get['placeholder']).'-'.($get['pos']+1).'" '.$sel.'>'._nach.' -> '.navi_name(string::decode($get['name'])).'</option>';
        }

        $qryn = db("SELECT * FROM ".dba::get('navi')."
                    WHERE editor = '".convert::ToInt($_GET['id'])."'");
        $getn = _fetch($qryn);

        if($gets['html'] == "1") $checked = "checked=\"checked\"";

        $show = show($dir."/form_editor", array("head" => _editor_edit_head,
                                                "what" => _button_value_edit,
                                                "bbcode" => _bbcode,
                                                "preview" => _preview,
                                                "titel" => _titel,
                                                "e_titel" => string::decode($gets['titel']),
                                                "e_inhalt" => string::decode($gets['text']),
                                                "checked" => $checked,
                                                "pos" => _position,
                                                "name" => _editor_linkname,
                                                "n_name" => string::decode($getn['name']),
                                                "position" => $position,
                                                "ja" => _yes,
                                                "nein" => _no,
                                                "wichtig" => _navi_wichtig,
                                                "error" => "",
                                                "allow_html" => _editor_allow_html,
                                                "inhalt" => _inhalt,
                                                "do" => "editsite&amp;id=".$_GET['id'].""));
      } elseif($_GET['do'] == "editsite") {
        if(empty($_POST['titel']) || empty($_POST['inhalt']) || $_POST['pos'] == "lazy")
        {
          if(empty($_POST['titel'])) $error = _empty_titel;
          elseif(empty($_POST['inhalt'])) $error = _empty_editor_inhalt;
          elseif($_POST['pos'] == "lazy") $error = _navi_no_pos;

          $error = show("errors/errortable", array("error" => $error));

          if(isset($_POST['html'])) $checked = "checked=\"checked\"";

          $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                     ORDER BY s1.name, s2.pos");
          $thiskat = '';
          while($get = _fetch($qry))
          {
            if($thiskat != $get['kat']) {
              $position .= '
                <option class="dropdownKat" value="lazy">'.string::decode($get['katname']).'</option>
                <option value="'.string::decode($get['placeholder']).'-1">-> '._admin_first.'</option>
              ';
            }
            $thiskat = $get['kat'];
            $sel = ($get['editor'] == $_GET['id']) ? 'selected="selected"' : '';

            $position .= empty($get['name']) ? '' : '<option value="'.string::decode($get['placeholder']).'-'.($get['pos']+1).'" '.$sel.'>'._nach.' -> '.navi_name(string::decode($get['name'])).'</option>';
          }

          $show = show($dir."/form_editor", array("head" => _editor_edit_head,
                                                  "what" => _button_value_edit,
                                                  "bbcode" => _bbcode,
                                                  "preview" => _preview,
                                                  "error" => $error,
                                                  "checked" => $checked,
                                                  "pos" => _position,
                                                  "ja" => _yes,
                                                  "nein" => _no,
                                                  "name" => _editor_linkname,
                                                  "position" => $position,
                                                  "n_name" => string::decode($_POST['name']),
                                                  "wichtig" => _navi_wichtig,
                                                  "titel" => _titel,
                                                  "e_titel" => string::decode($_POST['titel']),
                                                  "e_inhalt" => string::decode($_POST['inhalt']),
                                                  "allow_html" => _editor_allow_html,
                                                  "inhalt" => _inhalt,
                                                  "do" => "editsite&amp;id=".$_GET['id'].""));
        } else {
          $qry = db("UPDATE ".dba::get('sites')."
                     SET `titel` = '".string::encode($_POST['titel'])."',
                         `text`  = '".string::encode($_POST['inhalt'])."',
                         `html`   = '".convert::ToInt($_POST['html'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          if($_POST['pos'] == "1" || "2") $sign = ">= ";
          else $sign = "> ";

          $kat = preg_replace('/-(\d+)/','',$_POST['pos']);
          $pos = preg_replace("=nav_(.*?)-=","",$_POST['pos']);

          $url = "../sites/?show=".$_GET['id']."";

          $posi = db("UPDATE ".dba::get('navi')."
                      SET `pos` = pos+1
                      WHERE pos ".$sign." '".convert::ToInt($pos)."'");

          $posi = db("UPDATE ".dba::get('navi')."
                      SET `pos`     = '".convert::ToInt($pos)."',
                          `kat`     = '".string::encode($kat)."',
                          `name`    = '".string::encode($_POST['name'])."',
                          `url`     = '".string::encode($url)."'
                      WHERE editor = '".convert::ToInt($_GET['id'])."'");

          $show = info(_site_edited, "?admin=editor");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".dba::get('sites')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $qry = db("DELETE FROM ".dba::get('navi')."
                   WHERE editor = '".convert::ToInt($_GET['id'])."'");
        $show = info(_editor_deleted, "?admin=editor");
      } else {
        $qry = db("SELECT * FROM ".dba::get('sites')."");
        while($get = _fetch($qry))
        {
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=editor&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=editor&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_site));

          $show_ .= show($dir."/editor_show", array("name" => "<a href='../sites/?show=".$get['id']."'>".string::decode($get['titel'])."</a>",
                                                    "del" => $delete,
                                                    "edit" => $edit,
                                                    "class" => $class));
        }

        $show = show($dir."/editor", array("head" => _editor_head,
                                           "show" => $show_,
                                           "add" => _editor_add_head,
                                           "edit" => _editicon_blank,
                                           "del" => _deleteicon_blank,
                                           "name" => _editor_name));
      }