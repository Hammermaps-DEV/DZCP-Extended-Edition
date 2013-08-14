<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._news_admin_head;
      if($_GET['do'] == "add")
      {
        $qryk = db("SELECT * FROM ".dba::get('newskat')."");
        while($getk = _fetch($qryk))
        { $kat .= show(_select_field, array("value" => $getk['id'], "sel" => "", "what" => string::decode($getk['kategorie']))); }
        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                              "month" => dropdown("month",date("m",time())),
                                                      "year" => dropdown("year",date("Y",time()))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                      "minute" => dropdown("minute",date("i",time())),
                                                    "uhr" => _uhr));

        $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                "day" => dropdown("day",date("d",time())),
                                                                                                                "month" => dropdown("month",date("m",time())),
                                                                                                                "year" => dropdown("year",date("Y",time()))));

                $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                "hour" => dropdown("hour",date("H",time())),
                                                                                                                "minute" => dropdown("minute",date("i",time())),
                                                                                                                "uhr" => _uhr));
                $show = show($dir."/news_form", array("head" => _admin_news_head,
                                              "nautor" => _autor,
                                              "autor" => autor(),
                                              "nkat" => _news_admin_kat,
                                              "n_newspic" => "",
                                              "delnewspic" => "",
                                              "kat" => $kat,
                                              "preview" => _preview,
                                              "ntitel" => _titel,
                                              "do" => "insert",
                                              "ntext" => _eintrag,
                                              "selr_nc" => 'selected="selected"',
                                              "error" => "",
                                              "titel" => "",
                                              "newstext" => "",
                                              "morenews" => "",
                                              "link1" => "",
                                              "link2" => "",
                                              "link3" => "",
                                              "url1" => "",
                                              "url2" => "",
                                              "url3" => "",
                                              "klapplink" => "",
                                              "sticky" => "",
                                              "getsticky" => _news_get_sticky,
                                              "button" =>  _button_value_add,
                                              "nklapptitel" => _news_admin_klapptitel,
                                              "nmore" => _news_admin_more,
                                              "linkname" => _linkname,
                                              "interna" => _news_admin_intern,
                                              "intern" => "",
                                              "till" => _news_sticky_till,
                                              "dropdown_time" => $dropdown_time,
                                              "dropdown_date" => $dropdown_date,
                                                                                            "gettimeshift" => _news_get_timeshift,
                                                                                            "from" => _news_timeshift_from,
                                                                                            "timeshift_date" => $timeshift_date,
                                              "timeshift_time" => $timeshift_time,
                                                                                            "timeshift" => "",
                                              "nurl" => _url));
      } elseif($_GET['do'] == "insert") {
          if(empty($_POST['titel']) || empty($_POST['newstext']))
            {
              if(empty($_POST['titel'])) $error = _empty_news_title;
              elseif(empty($_POST['newstext'])) $error = _empty_news;

          $qryk = db("SELECT * FROM ".dba::get('newskat')."");
          while($getk = _fetch($qryk))
          {
            $kat .= show(_select_field, array("value" => $getk['id'], "sel" => ($_POST['kat'] == $getk['id'] ? 'selected="selected"' : ''), "what" => string::decode($getk['kategorie'])));
          }

              $error = show("errors/errortable", array("error" => $error));
              if($_POST['intern']) $int = "checked=\"checked\"";
          if($_POST['sticky']) $sticky = "checked=\"checked\"";
                    if($_POST['timeshift']) $timeshift = "checked=\"checked\"";


          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
                                                                "month" => dropdown("month",$_POST['m']),
                                                        "year" => dropdown("year",$_POST['j'])));

          $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",$_POST['h']),
                                                        "minute" => dropdown("minute",$_POST['min']),
                                                      "uhr" => _uhr));

                    $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                    "day" => dropdown("day",$_POST['t_ts']),
                                                                                                                    "month" => dropdown("month",$_POST['m_ts']),
                                                                                                                    "year" => dropdown("year",$_POST['j_ts'])));

                    $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                    "hour" => dropdown("hour",$_POST['h_ts']),
                                                                                                                    "minute" => dropdown("minute",$_POST['min_ts']),
                                                                                                                    "uhr" => _uhr));
                    $selr_nc = ($_POST['comments'] ? 'selected="selected"' : '');
                    $show = show($dir."/news_form", array("head" => _admin_news_head,
                                                "nautor" => _autor,
                                                "autor" => autor(),
                                                "nkat" => _news_admin_kat,
                            "n_newspic" => "",
                            "delnewspic" => "",
                                                "kat" => $kat,
                                                "preview" => _preview,
                                                "do" => "insert",
                                                "ntitel" => _titel,
                                                "selr_nc" => $selr_nc,
                                                "titel" => string::decode($_POST['titel']),
                                                "newstext" => string::decode($_POST['newstext']),
                                                "morenews" => string::decode($_POST['morenews']),
                                                "link1" => string::decode($_POST['link1']),
                                                "link2" => string::decode($_POST['link2']),
                                                "link3" => string::decode($_POST['link3']),
                                                "url1" => $_POST['url1'],
                                                "url2" => $_POST['url2'],
                                                "url3" => $_POST['url3'],
                                                "klapplink" => string::decode($_POST['klapptitel']),
                                                "ntext" => _eintrag,
                                                "button" => _button_value_add,
                                                "error" => $error,
                                                "nklapptitel" => _news_admin_klapptitel,
                                                "nmore" => _news_admin_more,
                                                "linkname" => _linkname,
                                                                      "intern" => $int,
                                                "sticky" => $sticky,
                                                "getsticky" => _news_get_sticky,
                                                "till" => _news_sticky_till,
                                                "dropdown_date" => $dropdown_date,
                                                "dropdown_time" => $dropdown_time,
                                                                      "interna" => _news_admin_intern,
                                                                                                "timeshift_date" => $timeshift_date,
                                                  "timeshift_time" => $timeshift_time,
                                                                                                "timeshift" => $timeshift,
                                                                                                "gettimeshift" => _news_get_timeshift,
                                                                                              "from" => _news_timeshift_from,
                                                "nurl" => _url));
          } else {
          if($_POST['sticky']) $stickytime = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

                    if($_POST['timeshift']){
                        $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                        $timeshift = "`timeshift` = '1',";
                        $public = "`public` = '1',";
                        $datum = "`datum` = '".convert::ToInt($timeshifttime)."',";
                    } else {
                      $timeshift = "";
                        $public = '';
                        $datum = '';
                    }


                $qry = db("INSERT INTO ".dba::get('news')."
                     SET `autor`      = '".userid()."',
                         `kat`        = '".convert::ToInt($_POST['kat'])."',
                         `titel`      = '".string::encode($_POST['titel'])."',
                         `text`       = '".string::encode($_POST['newstext'])."',
                         `klapplink`  = '".string::encode($_POST['klapptitel'])."',
                         `klapptext`  = '".string::encode($_POST['morenews'])."',
                         `link1`      = '".string::encode($_POST['link1'])."',
                         `link2`      = '".string::encode($_POST['link2'])."',
                         `link3`      = '".string::encode($_POST['link3'])."',
                         `url1`       = '".links($_POST['url1'])."',
                         `url2`       = '".links($_POST['url2'])."',
                         `url3`       = '".links($_POST['url3'])."',
                         `comments`   = '".convert::ToInt($_POST['comments'])."',
                         `intern`     = '".convert::ToInt($_POST['intern'])."',
                         ".$timeshift."
                                                 ".$public."
                                                 ".$datum."
                                                 `sticky`     = '".convert::ToInt($stickytime)."'");

                if($_FILES['newspic']['tmp_name']) {
                    $tmpname = $_FILES['newspic']['tmp_name'];
                    if(file_exists(basePath.'/inc/images/uploads/news/'.intval($_GET['id']).'.jpg')){
                        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                        @copy($tmpname, basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                        @unlink($tmpname);
                    }else{
                        @copy($tmpname, basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                        @unlink($tmpname);
                    }
                }

          $show = info(_news_sended, "?admin=newsadmin");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".dba::get('news')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $qryk = db("SELECT * FROM ".dba::get('newskat')."");
        while($getk = _fetch($qryk))
        {
            $kat .= show(_select_field, array("value" => $getk['id'], "sel" => ($get['kat'] == $getk['id'] ? 'selected="selected"' : ''), "what" => string::decode($getk['kategorie'])));
        }
        $do = show(_news_edit_link, array("id" => $_GET['id']));

        if($get['intern'] == 1) $int = "checked=\"checked\"";
                if($get['timeshift'] == 1) $timeshift = "checked=\"checked\"";
        if($get['sticky'] != 0)
        {
          $sticky = 'checked="checked"';
          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['sticky'])),
                                                                "month" => dropdown("month",date("m",$get['sticky'])),
                                                        "year" => dropdown("year",date("Y",$get['sticky']))));

          $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['sticky'])),
                                                        "minute" => dropdown("minute",date("i",$get['sticky'])),
                                                      "uhr" => _uhr));
        } else {
          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                                "month" => dropdown("month",date("m",time())),
                                                        "year" => dropdown("year",date("Y",time()))));

          $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                        "minute" => dropdown("minute",date("i",time())),
                                                      "uhr" => _uhr));
        }

                if($get['timeshift'] != 0)
        {
          $timeshift = 'checked="checked"';
                    $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                    "day" => dropdown("day",date("d",$get['datum'])),
                                                                                                                    "month" => dropdown("month",date("m",$get['datum'])),
                                                                                                                    "year" => dropdown("year",date("Y",$get['datum']))));

                    $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                    "hour" => dropdown("hour",date("H",$get['datum'])),
                                                                                                                    "minute" => dropdown("minute",date("i",$get['datum'])),
                                                                                                                    "uhr" => _uhr));
                } else {
          $timeshift = '';
                    $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                    "day" => dropdown("day",date("d",time())),
                                                                                                                    "month" => dropdown("month",date("m",time())),
                                                                                                                    "year" => dropdown("year",date("Y",time()))));

                    $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                    "hour" => dropdown("hour",date("H",time())),
                                                                                                                    "minute" => dropdown("minute",date("i",time())),
                                                                                                                    "uhr" => _uhr));
                }

                if(file_exists(basePath.'/inc/images/uploads/news/'.$_GET['id'].'.jpg')){
                    $newsimage = img_size('news/'.$_GET['id'].'.jpg')."<br /><br />";
                    $delnewspic = '<a href="?admin=newsadmin&do=delnewspic&id='.$_GET['id'].'">'._newspic_del.'</a><br /><br />';
                }else{
                    $newsimage = "";
                    $delnewspic = "";
                }

        $selr_nc = ($get['comments'] ? 'selected="selected"' : '');
        $show = show($dir."/news_form", array("head" => _admin_news_edit_head,
                                              "nautor" => _autor,
                                              "autor" => autor($get['autor']),
                                              "nkat" => _news_admin_kat,
                                              "kat" => $kat,
                                              "do" => $do,
                "n_newspic" => $newsimage,
                "delnewspic" => $delnewspic,
                                              "preview" => _preview,
                                              "ntitel" => _titel,
                                              "titel" => string::decode($get['titel']),
                                              "newstext" => string::decode($get['text']),
                                              "morenews" => string::decode($get['klapptext']),
                                              "link1" => string::decode($get['link1']),
                                              "link2" => string::decode($get['link2']),
                                              "link3" => string::decode($get['link3']),
                                              "url1" => $get['url1'],
                                              "url2" => $get['url2'],
                                              "url3" => $get['url3'],
                                              "klapplink" => string::decode($get['klapplink']),
                                              "selr_nc" => $selr_nc,
                                              "dropdown_date" => $dropdown_date,
                                              "dropdown_time" => $dropdown_time,
                                                                                            "timeshift_date" => $timeshift_date,
                                              "timeshift_time" => $timeshift_time,
                                                                                            "timeshift" => $timeshift,
                                              "ntext" => _eintrag,
                                              "error" => "",
                                              "button" => _button_value_edit,
                                              "nklapptitel" => _news_admin_klapptitel,
                                              "nmore" => _news_admin_more,
                                              "linkname" => _linkname,
                                                                                         "intern" => $int,
                                              "sticky" => $sticky,
                                              "getsticky" => _news_get_sticky,
                                              "till" => _news_sticky_till,
                                                                                            "gettimeshift" => _news_get_timeshift,
                                                                                            "from" => _news_timeshift_from,
                                              "day" => $day,
                                              "month" => $month,
                                              "year" => $year,
                                              "hour" => $hour,
                                              "minute" => $minute,
                                                                    "interna" => _news_admin_intern,
                                              "nurl" => _url));
      } elseif($_GET['do'] == "editnews") {
        if($_POST)
        {
          if($_POST['sticky']) $stickytime = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

                    if($_POST['timeshift']){
                        $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                        $timeshift = "`timeshift` = '1',";
                        $public = "`public` = '1',";
                        $datum = "`datum` = '".convert::ToInt($timeshifttime)."',";
                    } else {
                      $timeshift = "";
                        $public = '';
                        $datum = '';
                    }

          $qry = db("UPDATE ".dba::get('news')."
                     SET `kat`        = '".convert::ToInt($_POST['kat'])."',
                         `titel`      = '".string::encode($_POST['titel'])."',
                         `text`       = '".string::encode($_POST['newstext'])."',
                         `klapplink`  = '".string::encode($_POST['klapptitel'])."',
                         `klapptext`  = '".string::encode($_POST['morenews'])."',
                         `link1`      = '".string::encode($_POST['link1'])."',
                         `url1`       = '".links($_POST['url1'])."',
                         `link2`      = '".string::encode($_POST['link2'])."',
                         `url2`       = '".links($_POST['url2'])."',
                         `link3`      = '".string::encode($_POST['link3'])."',
                                   `intern`     = '".convert::ToInt($_POST['intern'])."',
                         `url3`       = '".links($_POST['url3'])."',
                                                 ".$timeshift."
                                                 ".$public."
                                                 ".$datum."
                         `comments`   = '".convert::ToInt($_POST['comments'])."',
                         `sticky`     = '".convert::ToInt($stickytime)."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
        }
        $show = info(_news_edited, "?admin=newsadmin");
      } elseif($_GET['do'] == 'public') {
        if($_GET['what'] == 'set')
        {
          $upd = db("UPDATE ".dba::get('news')."
                     SET `public` = '1',
                                      `datum`  = '".time()."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
        } elseif($_GET['what'] == 'unset') {
          $upd = db("UPDATE ".dba::get('news')."
                     SET `public` = '0'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
        }

        header("Location: ?admin=newsadmin");
      } elseif($_GET['do'] == "delete") {
        $del = db("DELETE FROM ".dba::get('news')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $del = db("DELETE FROM ".dba::get('newscomments')."
                   WHERE news = '".convert::ToInt($_GET['id'])."'");
        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");

        $show = info(_news_deleted, "?admin=newsadmin");
      } else {
        if(isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;

        $entrys = cnt(dba::get('news'));
        $qry = db("SELECT * FROM ".dba::get('news')." ORDER BY `public` ASC, `datum` DESC LIMIT ".($page - 1)*($maxadminnews=config('m_adminnews')).",".$maxadminnews."");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=newsadmin&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=newsadmin&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_news));
          $titel = show(_news_show_link, array("titel" => string::decode(cut($get['titel'],config('l_newsadmin'))),
                                               "id" => $get['id']));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          if($get['intern'] == "1") $intern = _votes_intern;
          else $intern = "";
          if($get['sticky'] == "0") $sticky = "";
          else $sticky = _news_sticky;

          $public = ($get['public'] == 1)
               ? '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/public.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/nonpublic.gif" alt="" title="'._public.'" /></a>';
          if(empty($get['datum'])) $datum = _no_public;
          else $datum = date("d.m.y H:i", $get['datum'])._uhr;

          $show_ .= show($dir."/admin_show", array("date" => $datum,
                                                   "titel" => $titel,
                                                   "class" => $class,
                                                   "autor" => autor($get['autor']),
                                                       "intnews" => $intern,
                                                   "sticky" => $sticky,
                                                   "public" => $public,
                                                   "edit" => $edit,
                                                   "delete" => $delete));
        }
        $nav = nav($entrys,$maxadminnews,"?admin=newsadmin");
        $show = show($dir."/admin_news", array("head" => _news_admin_head,
                                               "nav" => $nav,
                                               "autor" => _autor,
                                               "titel" => _titel,
                                               "val" => "newsadmin",
                                               "date" => _datum,
                                               "show" => $show_,
                                               "edit" => _editicon_blank,
                                               "delete" => _deleteicon_blank,
                                               "add" => _admin_news_head));
      }