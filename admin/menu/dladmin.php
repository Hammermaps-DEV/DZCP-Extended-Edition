<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._dl;
      if($_GET['do'] == "new")
      {
        $qry = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name");
        while($get = _fetch($qry))
        {
          $kats .= show(_select_field, array("value" => $get['id'], "what" => string::decode($get['name']), "sel" => ""));
        }

        $files = get_files(basePath.'/downloads/files/',false,true); $dl = '';
        foreach($files as $file)
        { $dl .= show(_downloads_files_exists, array("dl" => $file, "sel" => "")); }

        $show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head,
                                            "ddownload" => "",
                                             "durl" => "",
                                             "oder" => _or,
                                             "file" => $dl,
                                             "nothing" => "",
                                             "selr_dc" => 'selected="selected"',
                                             "nofile" => _downloads_nofile,
                                             "lokal" => _downloads_lokal,
                                             "what" => _button_value_add,
                                             "do" => "add",
                                             "exist" => _downloads_exist,
                                             "dbeschreibung" => "",
                                             "kat" => _downloads_kat,
                                             "kats" => $kats,
                                             "url" => _downloads_url,
                                             "beschreibung" => _beschreibung,
                                             "download" => _downloads_name));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['download']) || empty($_POST['url']))
        {
          if(empty($_POST['download'])) $show = error(_downloads_empty_download);
          elseif(empty($_POST['url']))  $show = error(_downloads_empty_url);
        } else {

          if(preg_match("#^www#i",$_POST['url'])) $dl = links($_POST['url']);
          else                                    $dl = string::encode($_POST['url']);

          $qry = db("INSERT INTO ".dba::get('downloads')."
                     SET `download`     = '".string::encode($_POST['download'])."',
                         `url`          = '".$dl."',
                         `date`         = '".time()."',
                         `comments`     = '".convert::ToInt($_POST['comments'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                         `kat`          = '".convert::ToInt($_POST['kat'])."'");

          $show = info(_downloads_added, "?admin=dladmin");
        }
      }
      elseif($_GET['do'] == "edit")
      {
        $qry  = db("SELECT * FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $qryk = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name");
        while($getk = _fetch($qryk))
        {
            $kats .= show(_select_field, array("value" => $getk['id'], "what" => string::decode($getk['name']), "sel" => ($getk['id'] == $get['kat'] ? 'selected="selected"' : '')));
        }

        $selr_dc = ($get['comments'] ? 'selected="selected"' : '');
        $show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head_edit,
                                            "ddownload" => string::decode($get['download']),
                                            "durl" => string::decode($get['url']),
                                            "file" => $dl,
                                            "selr_dc" => $selr_dc,
                                            "lokal" => _downloads_lokal,
                                            "exist" => _downloads_exist,
                                            "nothing" => _nothing,
                                            "nofile" => _downloads_nofile,
                                            "oder" => _or,
                                            "dbeschreibung" => string::decode($get['beschreibung']),
                                            "kat" => _downloads_kat,
                                            "what" => _button_value_edit,
                                            "do" => "editdl&amp;id=".$_GET['id']."",
                                            "kats" => $kats,
                                            "url" => _downloads_url,
                                            "beschreibung" => _beschreibung,
                                            "download" => _downloads_name));
      } elseif($_GET['do'] == "editdl") {
        if(empty($_POST['download']) || empty($_POST['url']))
        {
          if(empty($_POST['download'])) $show = error(_downloads_empty_download);
          elseif(empty($_POST['url']))  $show = error(_downloads_empty_url);
        } else {
          if(preg_match("#^www#i",$_POST['url'])) $dl = links($_POST['url']);
          else                                    $dl = string::encode($_POST['url']);

          $qry = db("UPDATE ".dba::get('downloads')."
                     SET `download`     = '".string::encode($_POST['download'])."',
                         `url`          = '".$dl."',
                         `comments`     = '".convert::ToInt($_POST['comments'])."',
                         `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                         `date`         = '".time()."',
                         `kat`          = '".convert::ToInt($_POST['kat'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_downloads_edited, "?admin=dladmin");
        }
      }
      elseif($_GET['do'] == "delete")
      {
        db("DELETE FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        db("DELETE FROM ".dba::get('dl_comments')." WHERE download = '".convert::ToInt($_GET['id'])."'");
        $show = info(_downloads_deleted, "?admin=dladmin");
      }
      else
      {
        $qry = db("SELECT * FROM ".dba::get('downloads')." ORDER BY id");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=dladmin&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=dladmin&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_dl));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/downloads_show", array("id" => $get['id'],
                                                       "dl" => string::decode($get['download']),
                                                       "class" => $class,
                                                       "edit" => $edit,
                                                       "delete" => $delete
                                                       ));
        }

        $show = show($dir."/downloads", array("head" => _dl,
                                              "date" => _datum,
                                              "titel" => _dl_file,
                                              "add" => _downloads_admin_head,
                                              "show" => $show_));
      }