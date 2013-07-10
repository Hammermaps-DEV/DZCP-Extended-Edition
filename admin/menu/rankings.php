<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._config_rankings;
      if($_GET['do'] == "add")
      {
        $qrys = db("SELECT * FROM ".dba::get('squads')."
                    WHERE status = '1'
                    ORDER BY game ASC");
        while($gets = _fetch($qrys))
        {
          $squads .= show(_select_field_ranking_add, array("what" => string::decode($gets['name']),
                                                                                 "value" => $gets['id'],
                                                           "icon" => $gets['icon'],
                                                           "sel" => ""));
        }
        $show = show($dir."/form_rankings", array("head" => _rankings_add_head,
                                                  "do" => "addranking",
                                                  "what" => _button_value_add,
                                                  "squad" => _rankings_squad,
                                                  "league" => _rankings_league,
                                                  "rank" => _rankings_admin_place,
                                                  "squads" => $squads,
                                                  "e_league" => "",
                                                  "e_rank" => "",
                                                  "e_url" => "",
                                                  "url" => _rankings_teamlink));
      } elseif($_GET['do'] == "addranking") {
        if(empty($_POST['league']) || empty($_POST['url']) || empty($_POST['rank']))
        {
          if(empty($_POST['league']))   $show = error(_error_empty_league);
          elseif(empty($_POST['url']))  $show = error(_error_empty_url);
          elseif(empty($_POST['rank'])) $show = error(_error_empty_rank);
        } else {
          $qry = db("INSERT INTO ".dba::get('rankings')."
                     SET `league`   = '".string::encode($_POST['league'])."',
                         `squad`    = '".string::encode($_POST['squad'])."',
                         `url`      = '".links($_POST['url'])."',
                         `rank`     = '".convert::ToInt($_POST['rank'])."',
                         `postdate` = '".time()."'");

          $show = info(_ranking_added, "?admin=rankings");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('rankings')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $qrys = db("SELECT * FROM ".dba::get('squads')." WHERE status = '1' ORDER BY game ASC");
        while($gets = _fetch($qrys))
        {
            $squads .= show(_select_field_ranking_add, array("what" => string::decode($gets['name']), "value" => $gets['id'], "icon" => $gets['icon'], "sel" => ($get['squad'] == $gets['id'] ? 'selected="selected"' : '')));
        }

        $show = show($dir."/form_rankings", array("head" => _rankings_edit_head,
                                                  "do" => "editranking&amp;id=".$_GET['id']."",
                                                  "what" => _button_value_edit,
                                                  "squad" => _rankings_squad,
                                                  "league" => _rankings_league,
                                                  "rank" => _rankings_admin_place,
                                                  "squads" => $squads,
                                                  "e_league" => string::decode($get['league']),
                                                  "e_rank" => $get['rank'],
                                                  "e_url" => string::decode($get['url']),
                                                  "url" => _rankings_teamlink));
      } elseif($_GET['do'] == "editranking") {
        if(empty($_POST['league']) || empty($_POST['url']) || empty($_POST['rank']))
        {
          if(empty($_POST['league']))   $show = error(_error_empty_league);
          elseif(empty($_POST['url']))  $show = error(_error_empty_url);
          elseif(empty($_POST['rank'])) $show = error(_error_empty_rank);
        } else {
          $qry = db("SELECT rank FROM ".dba::get('rankings')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
          $get = _fetch($qry);

          $qry = db("UPDATE ".dba::get('rankings')."
                     SET `league`       = '".string::encode($_POST['league'])."',
                         `squad`        = '".string::encode($_POST['squad'])."',
                         `url`          = '".links($_POST['url'])."',
                         `rank`         = '".convert::ToInt($_POST['rank'])."',
                         `lastranking`  = '".convert::ToInt($get['rank'])."',
                         `postdate`     = '".time()."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

          $show = info(_ranking_edited, "?admin=rankings");
        }
      } elseif($_GET['do'] == "delete") {
        $del = db("DELETE FROM ".dba::get('rankings')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_ranking_deleted, "?admin=rankings");
      } else {
      $qry = db("SELECT s1.*,s2.name,s2.id AS sqid FROM ".dba::get('rankings')." AS s1
                 LEFT JOIN ".dba::get('squads')." AS s2
                 ON s1.squad = s2.id
                 ORDER BY s1.postdate DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=rankings&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=rankings&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_ranking));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/rankings_show", array("squad" => string::decode($get['name']),
                                                      "league" => string::decode($get['league']),
                                                      "id" => $get['sqid'],
                                                      "class" => $class,
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/rankings", array("head" => _config_rankings,
                                             "league" => _cw_head_liga,
                                             "squad" => _cw_head_squad,
                                             "show" => $show_,
                                             "add" => _rankings_add_head
                                             ));
      }