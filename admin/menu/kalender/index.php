<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

  $where = $where.': '._kalender_head;
    if($_GET['do'] == "add")
    {
      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                            "month" => dropdown("month",date("m",time())),
                                                    "year" => dropdown("year",date("Y",time()))));

      $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                    "minute" => dropdown("minute",date("i",time())),
                                                  "uhr" => _uhr));
      $show = show($dir."/form_kalender", array("datum" => _datum,
                                                "event" => _kalender_event,
                                                "dropdown_time" => $dropdown_time,
                                                "dropdown_date" => $dropdown_date,
                                                "beschreibung" => _beschreibung,
                                                "what" => _button_value_add,
                                                "do" => "addevent",
                                                "k_event" => "",
                                                "k_beschreibung" => "",
                                                "head" => _kalender_admin_head));
    } elseif($_GET['do'] == "addevent") {
      if(empty($_POST['title']) || empty($_POST['event']))
      {
        if(empty($_POST['title']))     $show = error(_kalender_error_no_title);
        elseif(empty($_POST['event'])) $show = error(_kalender_error_no_event);
      } else {
        $time = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

        db("INSERT INTO ".dba::get('events')."
                      SET `datum` = '".convert::ToInt($time)."',
                          `title` = '".string::encode($_POST['title'])."',
                          `event` = '".string::encode($_POST['event'])."'");

        //Recache
        if(Cache::is_mem())
            Cache::delete('nav_eventbox');

        $show = info(_kalender_successful_added,"?index=admin&amp;admin=kalender");
      }
    } elseif($_GET['do'] == "edit") {
      $qry = db("SELECT * FROM ".dba::get('events')."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
      $get = _fetch($qry);

      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
                                                            "month" => dropdown("month",date("m",$get['datum'])),
                                                    "year" => dropdown("year",date("Y",$get['datum']))));

      $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['datum'])),
                                                    "minute" => dropdown("minute",date("i",$get['datum'])),
                                                  "uhr" => _uhr));
      $show = show($dir."/form_kalender", array("datum" => _datum,
                                                "event" => _kalender_event,
                                                "dropdown_time" => $dropdown_time,
                                                "dropdown_date" => $dropdown_date,
                                                "beschreibung" => _beschreibung,
                                                "what" => _button_value_edit,
                                                "do" => "editevent&amp;id=".$_GET['id'],
                                                "k_event" => string::decode($get['title']),
                                                "k_beschreibung" => string::decode($get['event']),
                                                "head" => _kalender_admin_head_edit));
    } elseif($_GET['do'] == "editevent") {
      if(empty($_POST['title']) || empty($_POST['event']))
      {
        if(empty($_POST['title']))     $show = error(_kalender_error_no_title);
        elseif(empty($_POST['event'])) $show = error(_kalender_error_no_event);
      } else {
        $time = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

        db("UPDATE ".dba::get('events')."
                      SET `datum` = '".convert::ToInt($time)."',
                          `title` = '".string::encode($_POST['title'])."',
                          `event` = '".string::encode($_POST['event'])."'
                      WHERE id = '".convert::ToInt($_GET['id'])."'");

        //Recache
        if(Cache::is_mem())
            Cache::delete('nav_eventbox');

        $show = info(_kalender_successful_edited,"?index=admin&amp;admin=kalender");
      }
    } elseif($_GET['do'] == "delete") {
      db("DELETE FROM ".dba::get('events')." WHERE id = '".convert::ToInt($_GET['id'])."'");

      //Recache
      if(Cache::is_mem())
          Cache::delete('nav_eventbox');

      $show = info(_kalender_deleted,"?index=admin&amp;admin=kalender");
    } else {
      $qry = db("SELECT * FROM ".dba::get('events')."
                 ORDER BY datum DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "index=admin&amp;admin=kalender&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "index=admin&amp;admin=kalender&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_kalender));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/kalender_show", array("datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                      "event" => string::decode($get['title']),
                                                      "time" => $get['datum'],
                                                      "id" => $get['sqid'],
                                                      "class" => $class,
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/kalender", array("head" => _kalender_admin_head,
                                             "date" => _datum,
                                             "titel" => _kalender_event,
                                             "show" => $show_,
                                             "add" => _kalender_admin_head_add
                                             ));
    }