<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._partners_head;
      if($_GET['do'] == "add")
      {
        $files = get_files(basePath.'/banner/partners/',false,true);
        foreach($files as $file)
        {
          $banners .= show(_partners_select_icons, array("icon" => $file,
                                                         "sel" => ""));
        }
        $show = show($dir."/form_partners", array("do" => "addbutton",
                                                  "head" => _partners_add_head,
                                                  "nothing" => "",
                                                  "banner" => _partners_button,
                                                  "link" => _link,
                                                  "e_link" => "",
                                                  "e_textlink" => "",
                                                  "or" => _or,
                                                  "textlink" => _partnerbuttons_textlink,
                                                  "banners" => $banners,
                                                  "what" => _button_value_add));
      } elseif($_GET['do'] == "addbutton") {
        if(empty($_POST['link']))
        {
          $show = error(_empty_url);
        } else {
          $qry = db("INSERT INTO ".dba::get('partners')."
                     SET `link`     = '".links($_POST['link'])."',
                         `banner`   = '".string::encode(empty($_POST['textlink']) ? $_POST['banner'] : $_POST['textlink'])."',
                         `textlink` = '".convert::ToInt(empty($_POST['textlink']) ? 0 : 1)."'");

          $show = info(_partners_added, "?admin=partners");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('partners')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $files = get_files(basePath.'/banner/partners/',false,true);
        foreach($files as $file)
        {
            $banners .= show(_partners_select_icons, array("icon" => $file, "sel" => (string::decode($get['banner']) == $file ? 'selected="selected"' : '')));
        }

        $show = show($dir."/form_partners", array("do" => "editbutton&amp;id=".$get['id']."",
                                                  "head" => _partners_edit_head,
                                                  "nothing" => "",
                                                  "banner" => _partners_button,
                                                  "link" => _link,
                                                  "e_link" => string::decode($get['link']),
                                                  "e_textlink" => (empty($get['textlink']) ? '' : string::decode($get['banner'])),
                                                  "or" => _or,
                                                  "textlink" => _partnerbuttons_textlink,
                                                  "banners" => $banners,
                                                  "what" => _button_value_edit));
      } elseif($_GET['do'] == "editbutton") {
        if(empty($_POST['link']))
        {
          $show = error(_empty_url);
        } else {
          $qry = db("UPDATE ".dba::get('partners')."
                     SET `link`     = '".links($_POST['link'])."',
                         `banner`   = '".string::encode(empty($_POST['textlink']) ? $_POST['banner'] : $_POST['textlink'])."',
                         `textlink` = '".convert::ToInt(empty($_POST['textlink']) ? 0 : 1)."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_partners_edited, "?admin=partners");
        }
      } elseif($_GET['do'] == "delete") {
        $del = db("DELETE FROM ".dba::get('partners')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_partners_deleted,"?admin=partners");
      } else {
        $qry = db("SELECT * FROM ".dba::get('partners')."
                   ORDER BY id");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=partners&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=partners&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_entry));

          $color = 1;
          $rlink = str_replace('http://', '', string::decode($get['link']));
          $button = '<img src="../banner/partners/'.string::decode($get['banner']).'" alt="'.$rlink.'" title="'.$rlink.'" />';
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/partners_show", array("class" => $class,
                                                      "button" => (empty($get['textlink']) ? $button : '<center>'._partnerbuttons_textlink.': <b>'.string::decode($get['banner']).'</b></center>'),
                                                      "link" => string::decode($get['link']),
                                                      "id" => $get['id'],
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/partners", array("head" => _partners_head,
                                             "add" => _partners_link_add,
                                             "show" => $show_,
                                             "edit" => _editicon_blank,
                                             "del" =>_deleteicon_blank,
                                             "link" => _link,
                                             "button" => _partners_button));
      }