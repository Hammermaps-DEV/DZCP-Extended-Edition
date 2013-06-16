<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._slider;

switch ($do)
{
    case 'new':
        $qry = db("SELECT * FROM ".dba::get('slideshow')." ORDER BY `pos` ASC;"); $positions = '';
        if(_rows($qry) >= 1)
        {
            while($get = _fetch($qry))
            { $positions .= show(_select_field, array("value" => $get['pos']+1, "what" => _nach.': '.$get['bez'], "sel" => "")); }
        }

        $infos = show(_slider_info, array("userpicsize" => config('upicsize')));
        $show = show($dir."/slideshow_form", array( "id" => "",
                                                    "error" => "",
                                                    "infos" => $infos,
                                                    "do" => "add",
                                                    "head" => _slider_admin_add,
                                                    "value" => _button_value_add,
                                                    "ja" => _yes,
                                                    "nein" => _no,
                                                    "bezeichnung" => _slider_bezeichnung,
                                                    "desc" => _slider_desc,
                                                    "tdesc" => '',
                                                    "t_zeichen" => _zeichen,
                                                    "noch" => _noch,
                                                    "url" => _slider_url,
                                                    "new_window" => _slider_new_window,
                                                    "pic" => _slider_pic,
                                                    "position" => _slider_position,
                                                    "first" => _slider_position_first,
                                                    "v_bezeichnung" => "",
                                                    "v_pos_none" => "",
                                                    "v_position" => $positions,
                                                    "v_url" => "http://",
                                                    "selected0" => "",
                                                    "selected1" => "",
                                                    "selected_txt" => "selected=\"selected\"",
                                                    "v_pic" => ""));
    break;

 /*
    case '':
    break;

    case '':
    break;

    case '':
    break;
    */

    default:
        $qry = db("SELECT * FROM ".dba::get('slideshow')." ORDER BY `pos` ASC");
        if(_rows($qry) >= 1)
        {
            $color = 1; $entry = '';
            while($get = _fetch($qry))
            {
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "admin=slideshow&amp;do=edit", "title" => _button_title_edit));
                $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "admin=slideshow&amp;do=delete", "title" => _button_title_del, "del" => convSpace(_slider_admin_del)));
                $entry .= show($dir."/slideshow_show", array("id" => $get['id'], "class" => $class, "bez" => $get['bez'], "edit" => $edit, "del" => $delete));
            }
        }
        else
            $entry = show(_no_entrys_yet, array("colspan" => "3"));

        $show = show($dir."/slideshow", array("show" => $entry));
    break;
}

if($_GET['do'] == 'add'){
    if(empty($_FILES['bild']['tmp_name']) || empty($_POST['bez']) || empty($_POST['url']) || $_POST['url'] == "http://") {
        if(!$_FILES['bild']['tmp_name']) $error = _slider_admin_error_nopic;
        elseif(empty($_POST['bez'])) $error = _slider_admin_error_empty_bezeichnung;
        elseif(empty($_POST['url']) OR $_POST['url'] == "http://") $error = _slider_admin_error_empty_url;

        $error = show("errors/errortable", array("error" => $error));

        if($_POST['target'] == 1) $selected = "selected=\"selected\"";
        if($_POST['showbez'] == 1) $selected_txt = "selected=\"selected\"";

        $qry = db("SELECT * FROM ".dba::get('slideshow')."
                  ORDER BY `pos` ASC;");
        while($get = _fetch($qry)) {
            $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                    "what" => _nach.': '.$get['bez'],
                                                    "sel" => ""));
        }

        $infos = show(_slider_info, array("userpicsize" => config('upicsize')));
        $show = show($dir."/slideshow_form", array("id" => "",
                                                "error" => $error,
                                                "infos" => $infos,
                                                "do" => "add",
                                                "head" => _slider_admin_add,
                                                "value" => _button_value_add,
                                                "ja" => _yes,
                                                "nein" => _no,
                                                "bezeichnung" => _slider_bezeichnung,
                                                "desc" => _slider_desc,
                                                "tdesc" => re($_POST['desc']),
                                                "t_zeichen" => _zeichen,
                                                "noch" => _noch,
                                                "url" => _slider_url,
                                                "new_window" => _slider_new_window,
                                                "pic" => _slider_pic,
                                                "position" => _slider_position,
                                                "first" => _slider_position_first,
                                                "v_bezeichnung" => re($_POST['bez']),
                                                "v_pos_none" => "",
                                                "v_position" => $positions,
                                                "v_url" => re($_POST['url']),
                                                "selected" => $selected,
                                                "selected_txt" => $selected_txt,
                                                "v_pic" => ""));
    } else {
        if($_POST['position'] == "1" || "2") $sign = ">= ";
        else  $sign = "> ";

        $posi = db("UPDATE ".dba::get('slideshow')."
                    SET `pos` = pos+1
                    WHERE `pos` ".$sign." '".intval($_POST['position'])."'");

        $qry = db("INSERT INTO ".dba::get('slideshow')."
                   SET `pos` = '".((int)$_POST['position'])."',
                       `bez` = '".up($_POST['bez'])."',
                       `showbez` = '".((int)($_POST['showbez']))."',
                       `desc` = '".up($_POST['desc'])."',
                       `url` = '".up($_POST['url'])."',
                       `target` = '".up($_POST['target'])."'");

        move_uploaded_file($_FILES['bild']['tmp_name'], basePath."/inc/images/uploads/slideshow/".database::get_insert_id().".jpg");
        $tmpname = $_FILES['bild']['tmp_name'];
        $show = info(_slider_admin_add_done, "?admin=slideshow");
    }
  }
  elseif($_GET['do'] == 'edit')
  {
    $qry = db("SELECT * FROM ".dba::get('slideshow')."
               WHERE `id` = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    $qrypos = db("SELECT * FROM ".dba::get('slideshow')."
                  WHERE `id` != '".intval($get['id'])."'
                  ORDER BY `pos` ASC");
    while($getpos = _fetch($qrypos)) {
        $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                "what" => _nach.': '.$getpos['bez'],
                                                "sel" => ""));
    }

    if($get['target'] == 1) $selected = "selected=\"selected\"";
    if($_POST['showbez'] == 1) $selected_txt = "selected=\"selected\"";

    $infos = show(_slider_info, array("userpicsize" => config('upicsize')));
    $show = show($dir."/slideshow_form", array("id" => re($get['id']),
                                               "error" => "",
                                                "infos" => $infos,
                                                "do" => "editdo",
                                                "head" => _slider_admin_edit,
                                                "value" => _button_value_edit,
                                                "ja" => _yes,
                                                "nein" => _no,
                                                "bezeichnung" => _slider_bezeichnung,
                                                "desc" => _slider_desc,
                                                "tdesc" => re($get['desc']),
                                                "t_zeichen" => _zeichen,
                                                "noch" => _noch,
                                                "url" => _slider_url,
                                                "new_window" => _slider_new_window,
                                                "pic" => _slider_pic,
                                                "position" => _slider_position,
                                                "first" => _slider_position_first,
                                                "v_bezeichnung" => re($get['bez']),
                                                "v_pos_none" => _slider_position_lazy,
                                                "v_position" => $positions,
                                                "v_url" => re($get['url']),
                                                "selected" => $selected,
                                                "selected_txt" => $selected_txt,
                                                "v_pic" => img_size('slideshow/'.$get['id'].'.jpg',100)."<br />"));
}elseif($_GET['do'] == 'editdo'){
    if(empty($_POST['bez']) || empty($_POST['url']) || $_POST['url'] == "http://") {
        if(empty($_POST['bez'])) $error = _slider_admin_error_empty_bezeichnung;
        elseif(empty($_POST['url']) OR $_POST['url'] == "http://") $error = _slider_admin_error_empty_url;

        $error = show("errors/errortable", array("error" => $error));

        if($_POST['target']) $selected = "selected=\"selected\"";
        if($_POST['showbez']) $selected_txt = "selected=\"selected\"";

        $infos = show(_slider_info, array("userpicsize" => config('upicsize')));
        $show = show($dir."/slideshow_form", array("id" => re($_POST['id']),
                                                    "error" => $error,
                                                    "infos" => $infos,
                                                    "do" => "editdo",
                                                    "head" => _slider_admin_edit,
                                                    "value" => _button_value_edit,
                                                    "ja" => _yes,
                                                    "nein" => _no,
                                                    "bezeichnung" => _slider_bezeichnung,
                                                    "desc" => _slider_desc,
                                                    "tdesc" => re($_POST['desc']),
                                                    "t_zeichen" => _zeichen,
                                                    "noch" => _noch,
                                                    "url" => _slider_url,
                                                    "new_window" => _slider_new_window,
                                                    "pic" => _slider_pic,
                                                    "position" => _slider_position,
                                                    "first" => _slider_position_first,
                                                    "v_bezeichnung" => re($_POST['bez']),
                                                    "v_pos_none" => _slider_position_lazy,
                                                    "v_position" => $positions,
                                                    "v_url" => re($_POST['url']),
                                                    "selected" => $selected,
                                                    "selected_txt" => $selected_txt,
                                                    "v_pic" => img_size('slideshow/'.$_POST['id'].'.jpg',100)."<br />"));
    } else {
        if($_POST['position'] != "lazy") {
        if($_POST['position'] == "1" || "2") $sign = ">= ";
        else  $sign = "> ";

            $posi = db("UPDATE ".dba::get('slideshow')."
                        SET `pos` = pos+1
                        WHERE `pos` ".$sign." '".intval($_POST['position'])."'");

            $pos = "`pos` = '".((int)$_POST['position'])."',";
        } else $pos = "";

        $qry = db("UPDATE ".dba::get('slideshow')."
                  SET ".$pos."
                      `bez` = '".up($_POST['bez'])."',
                      `url` = '".up($_POST['url'])."',
                      `desc` = '".up($_POST['desc'])."',
                      `target` = '".up($_POST['target'])."'
                  WHERE `id` = '".intval($_POST['id'])."'");

        if($_FILES['bild']['tmp_name'])
        {
            $tmpname = $_FILES['bild']['tmp_name'];
            @unlink(basePath."/inc/images/uploads/slideshow/".intval($_POST['id']).".jpg");
            move_uploaded_file($tmpname, basePath."/inc/images/uploads/slideshow/".intval($_POST['id']).".jpg");
        }

        $show = info(_slider_admin_edit_done, "?admin=slideshow");
    }
}
elseif($_GET['do'] == 'delete')
{
    db("DELETE FROM ".dba::get('slideshow')." WHERE `id` = '".intval($_GET['id'])."'");
    @unlink(basePath."/inc/images/uploads/slideshow/".intval($_GET['id']).".jpg");
    $show = info(_slider_admin_del_done, "?admin=slideshow");
}