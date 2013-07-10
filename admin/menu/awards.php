<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._awards_head;
    if(!permission("awards"))
    {
        $show = error(_error_wrong_permissions);
    } else {
        if($_GET['do'] == "new")
      {
          $qry = db("SELECT * FROM ".dba::get('awards')."
                   ORDER BY game ASC");
          while($get = _fetch($qry))
        {
          $squads .= show(_awards_admin_add_select_field_squads, array("name" => $get['name'],
                                                                            "game" => $get['game'],
                                                                       "icon" => $get['icon'],
                                                                                                "id" => $get['id']));
        }

          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                                         "month" => dropdown("month",date("m",time())),
                                                        "year" => dropdown("year",date("Y",time()))));

        $show = show($dir."/form_awards", array("head" => _awards_admin_head_add,
                                                "date" => _awards_head_date,
                                                                  "squad" => _awards_head_squad,
                                                                  "event" => _awards_head_event,
                                                                  "url" => _awards_head_link,
                                                                  "place" => _awards_head_place,
                                                                  "prize" => _awards_head_prize,
                                                                  "squads" => $squads,
                                                                  "dropdown_date" => $dropdown_date,
                                                                 "do" => "add",
                                                "what" => _button_value_add,
                                                                  "award_event" => "",
                                                "award_url" => "",
                                                "award_place" => "",
                                                "award_prize" => ""));
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('awards')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

          $qrym = db("SELECT * FROM ".dba::get('awards')."
                    ORDER BY game");
          while($gets = _fetch($qrym))
          {
             $squads .= show(_awards_admin_edit_select_field_squads, array("id" => $gets['id'],
                                                                                                          "name" => string::decode($gets['name']),
                                                                                   "game" => string::decode($gets['game']),
                                                                        "icon" => string::decode($gets['icon']),
                                                                                                    "sel" => ($get['squad'] == $gets['id'] ? 'selected="selected"' : '')));
          }

          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['date'])),
                                                                        "month" => dropdown("month",date("m",$get['date'])),
                                                        "year" => dropdown("year",date("Y",$get['date']))));

        $show = show($dir."/form_awards", array("head" => _awards_admin_head_edit,
                                                "date" => _awards_head_date,
                                                                  "squad" => _awards_head_squad,
                                                                  "event" => _awards_head_event,
                                                                  "url" => _awards_head_link,
                                                                  "place" => _awards_head_place,
                                                                  "prize" => _awards_head_prize,
                                                "do" => "editaw&amp;id=".$_GET['id']."",
                                                "what" => _button_value_edit,
                                                                  "squads" => $squads,
                                                                  "dropdown_date" => $dropdown_date,
                                                                  "award_event" => string::decode($get['event']),
                                                                   "award_url" => $get['url'],
                                                                  "award_place" => string::decode($get['place']),
                                                                  "award_prize" => string::decode($get['prize'])));
      } elseif($_GET['do'] == "add") {
          if(empty($_POST['event']) || empty($_POST['url']))
        {
              if(empty($_POST['event']))
              {
                  $show = error(_awards_empty_event);
              } elseif(empty($_POST['url'])) {
                  $show = error(_awards_empty_url);
              }
        } else {
              if(empty($_POST['place'])) $place = "-";
              else $place = $_POST['place'];

              if(empty($_POST['prize'])) $prize = "-";
              else $prize = $_POST['prize'];

          $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

          $qry = db("INSERT INTO ".dba::get('awards')."
                     SET `date`     = '".convert::ToInt($datum)."',
                         `postdate` = '".time()."',
                         `squad`    = '".convert::ToInt($_POST['squad'])."',
                         `event`    = '".string::encode($_POST['event'])."',
                         `url`      = '".links($_POST['url'])."',
                         `place`    = '".string::encode($place)."',
                         `prize`    = '".string::encode($prize)."'");

          $show = info(_awards_admin_added, "?admin=awards");
        }
      } elseif($_GET['do'] == "editaw") {
          if(empty($_POST['event']) || empty($_POST['url']))
        {
              if(empty($_POST['event']))
              {
                  $index = error(_awards_empty_event);
              } elseif(empty($_POST['url'])) {
                  $index = error(_awards_empty_url);
              }
        } else {
              if(empty($_POST['place'])) $place = "-";
              else $place = $_POST['place'];
            }

        if(empty($_POST['prize'])) $prize = "-";
            else $prize = $_POST['prize'];

            $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

            $qry = db("UPDATE ".dba::get('awards')."
                   SET `date`   = '".convert::ToInt($datum)."',
                                 `squad`  = '".convert::ToInt($_POST['squad'])."',
                                 `event`  = '".string::encode($_POST['event'])."',
                       `url`    = '".links($_POST['url'])."',
                                 `place`  = '".string::encode($place)."',
                                 `prize`  = '".string::encode($prize)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_awards_admin_edited, "?admin=awards");
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".dba::get('awards')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_awards_admin_deleted, "?admin=awards");
      } else {
        $qry = db("SELECT * FROM ".dba::get('awards')."
                   ORDER BY date DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=awards&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=awards&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_award));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/awards_show", array("datum" => date("d.m.Y",$get['date']),
                                                    "award" => string::decode($get['event']),
                                                    "id" => $get['squad'],
                                                    "class" => $class,
                                                    "edit" => $edit,
                                                    "delete" => $delete));
        }

        $show = show($dir."/awards", array("head" => _awards_head,
                                           "date" => _datum,
                                           "titel" => _award,
                                           "show" => $show_,
                                           "add" => _awards_admin_head_add
                                           ));
      }
    }