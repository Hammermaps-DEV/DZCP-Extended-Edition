<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

      if($_GET['do'] == "new")
      {
        $show = show($dir."/form_linkus", array("head" => _linkus_admin_head,
                                                "link" => _linkus_link,
                                                "beschreibung" => _linkus_beschreibung,
                                                "art" => _linkus_art,
                                                "text" => _linkus_admin_textlink,
                                                "banner" => _linkus_admin_bannerlink,
                                                "bchecked" => "checked=\"checked\"",
                                                "tchecked" => "",
                                                "llink" => _linkus_bsp_target,
                                                "lbeschreibung" => _linkus_bsp_desc,
                                                "btext" => _linkus_text,
                                                "ltext" => _linkus_bsp_bannerurl,
                                                "what" => _button_value_add,
                                                "do" => "add"));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || empty($_POST['text']))
        {
          if(empty($_POST['link']))             $show = error(_linkus_empty_link);
          elseif(empty($_POST['beschreibung'])) $show = error(_linkus_empty_beschreibung);
          elseif(empty($_POST['text']))         $show = error(_linkus_empty_text);
        } else {
          $qry = db("INSERT INTO ".dba::get('linkus')."
                     SET `url`          = '".links($_POST['link'])."',
                         `text`         = '".string::encode($_POST['text'])."',
                         `banner`       = '".string::encode($_POST['banner'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."'");

          $show = info(_linkus_added, "?index=admin&amp;admin=linkus");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('linkus')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/form_linkus", array("head" => _linkus_admin_edit,
                                                "link" => _linkus_link,
                                                "beschreibung" => _linkus_beschreibung,
                                                "art" => _linkus_art,
                                                "text" => _linkus_admin_textlink,
                                                "banner" => _linkus_admin_bannerlink,
                                                "llink" => $get['url'],
                                                "lbeschreibung" => string::decode($get['beschreibung']),
                                                "btext" => _linkus_text,
                                                "ltext" => $get['text'],
                                                "what" => _button_value_edit,
                                                "do" => "editlink&amp;id=".$_GET['id'].""));
      } elseif($_GET['do'] == "editlink") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || empty($_POST['text']))
        {
          if(empty($_POST['link']))             $show = error(_linkus_empty_link);
          elseif(empty($_POST['beschreibung'])) $show = error(_linkus_empty_beschreibung);
          elseif(empty($_POST['text']))         $show = error(_linkus_empty_text);
        } else {
          $qry = db("UPDATE ".dba::get('linkus')."
                     SET `url`          = '".links($_POST['link'])."',
                         `text`         = '".string::encode($_POST['text'])."',
                         `banner`       = '".string::encode($_POST['banner'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_linkus_edited, "?index=admin&amp;admin=linkus");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".dba::get('linkus')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_linkus_deleted, "?index=admin&amp;admin=linkus");
      } else {
        $qry = db("SELECT * FROM ".dba::get('linkus')."
                   ORDER BY banner DESC");
        $cnt = 1;
        while($get = _fetch($qry))
        {
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $banner = show(_linkus_bannerlink, array("id" => $get['id'],
                                                   "banner" => string::decode($get['text'])));

          $edit = show("page/button_edit", array("id" => $get['id'],
                                                 "action" => "index=admin&amp;admin=linkus&amp;do=edit",
                                                 "title" => _button_title_edit));
          $delete = show("page/button_delete", array("id" => $get['id'],
                                                     "action" => "index=admin&amp;admin=linkus&amp;do=delete",
                                                     "title" => _button_title_del));

          $show_ .= show($dir."/linkus_show", array("class" => $class,
                                                    "beschreibung" => string::decode($get['beschreibung']),
                                                    "edit" => $edit,
                                                    "delete" => $delete,
                                                    "cnt" => $cnt,
                                                    "banner" => $banner,
                                                    "besch" => string::decode($get['beschreibung']),
                                                                          "url" => $get['url']));
          $cnt++;
        }

        $show = show($dir."/linkus", array("head" => _linkus_head,
                                           "show" => $show_,
                                           "add" => _linkus_admin_head));
      }