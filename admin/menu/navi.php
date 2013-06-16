<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._navi_head;

switch ($do)
{
    case 'add':
        $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat` ORDER BY s1.name, s2.pos");
        while($get = _fetch($qry))
        {
            if($thiskat != $get['kat']) {
                $position .= '
              <option class="dropdownKat" value="lazy">'.re($get['katname']).'</option>
              <option value="'.re($get['placeholder']).'-1">-> '._admin_first.'</option>';
            }
            $thiskat = $get['kat'];

            $position .= empty($get['name']) ? '' : '<option value="'.re($get['placeholder']).'-'.($get['pos']+1).'">'._nach.' -> '.navi_name(re($get['name'])).'</option>';
        }

        $show = show($dir."/form_navi", array("do" => "addnavi",
                "what" => _button_value_add,
                "head" => _navi_add_head,
                "ja" => _yes,
                "intern" => _config_forum_intern,
                "nein" => _no,
                "n_name" => "",
                "n_url" => "",
                "atarget" => "",
                "target" => _target,
                "position" => $position,
                "name" => _navi_name,
                "url" => _navi_url_to,
                "wichtig" => _navi_wichtig,
                "pos" => _posi));
    break;

    case 'addnavi':
        if(empty($_POST['name']))
        {
            $show = error(_navi_no_name);
        } elseif(empty($_POST['url'])) {
            $show = error(_navi_no_url);
        } elseif($_POST['pos'] == "lazy") {
            $show = error(_navi_no_pos);
        } else {
            if($_POST['pos'] == "1" || "2") $sign = ">= ";
            else $sign = "> ";

            $kat = preg_replace('/-(\d+)/','',$_POST['pos']);
            $pos = preg_replace("=nav_(.*?)-=","",$_POST['pos']);

            $posi = db("UPDATE ".dba::get('navi')."
                      SET `pos` = pos+1
                      WHERE pos ".$sign." '".convert::ToInt($pos)."'");

            $posi = db("INSERT INTO ".dba::get('navi')."
                      SET `pos`       = '".convert::ToInt($pos)."',
                          `kat`       = '".up($kat)."',
                          `name`      = '".up($_POST['name'])."',
                          `url`       = '".up($_POST['url'])."',
                          `shown`     = '1',
                          `target`    = '".convert::ToInt($_POST['target'])."',
                          `internal`  = '".convert::ToInt($_POST['internal'])."',
                          `type`      = '2',
                          `wichtig`   = '".convert::ToInt($_POST['wichtig'])."'");
            $show = info(_navi_added,"?admin=navi");
        }
    break;

    case 'delete':
        $qry = db("SELECT * FROM ".dba::get('navi')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $del = db("DELETE FROM ".dba::get('sites')."
                   WHERE id = '".convert::ToInt($get['editor'])."'");

        $del = db("DELETE FROM ".dba::get('navi')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_navi_deleted, "?admin=navi");
    break;

    case 'edit':
        $qry = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                   ORDER BY s1.name, s2.pos");
        $i = 1;
        $thiskat = '';
        while($get = _fetch($qry))
        {
            if($thiskat != $get['kat']) {
                $position .= '
              <option class="dropdownKat" value="lazy">'.re($get['katname']).'</option>
              <option value="'.re($get['placeholder']).'-1">-> '._admin_first.'</option>
            ';
            }
            $thiskat = $get['kat'];
            $sel[$i] = ($get['id'] == $_GET['id']) ? 'selected="selected"' : '';

            $position .= empty($get['name']) ? '' : '<option value="'.re($get['placeholder']).'-'.($get['pos']+1).'" '.$sel[$i].'>'._nach.' -> '.navi_name(re($get['name'])).'</option>';

            $i++;
        }

        $qry = db("SELECT * FROM ".dba::get('navi')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['type'] == "1")
        {
            $name = re($get['name']);
            $read = "readonly";
        } else {
            $name = re($get['name']);
            $read = "";
        }

        if($get['wichtig'] == "1") $selw = "selected=\"selected\"";
        if($get['shown'] == "1") $sels = "selected=\"selected\"";
        if($get['internal'] == "1") $seli = "selected=\"selected\"";
        if($get['target'] == "1") $target = "selected=\"selected\"";

        $show = show($dir."/form_navi_edit", array("name" => _navi_name,
                "url" => _navi_url_to,
                "wichtig" => _navi_wichtig,
                "pos" => _posi,
                "atarget" => $target,
                "target" => _target,
                "n_name" => $name,
                "n_url" => $get['url'],
                "what" => _button_value_edit,
                "do" => "editlink&amp;id=".$get['id']."",
                "ja" => _yes,
                "intern" => _config_forum_intern,
                "seli" => $seli,
                "sichtbar" => _navi_shown,
                "sels" => $sels,
                "position" => $position,
                "selw" => $selw,
                "read" => $read,
                "nein" => _no,
                "head" => _navi_edit_head));
    break;

    case 'editlink':
        if($_POST['pos'] == "1" || "2") $sign = ">= ";
        else $sign = "> ";

        $kat = preg_replace('/-(\d+)/','',$_POST['pos']);
        $pos = preg_replace("=nav_(.+)-=","",$_POST['pos']);

        $posi = db("UPDATE ".dba::get('navi')."
                    SET pos = pos+1
                    WHERE pos ".$sign." '".convert::ToInt($pos)."'");

        $posi = db("UPDATE ".dba::get('navi')."
                    SET `pos`       = '".convert::ToInt($pos)."',
                        `kat`       = '".up($kat)."',
                        `name`      = '".up($_POST['name'])."',
                        `url`       = '".up($_POST['url'])."',
                        `target`    = '".convert::ToInt($_POST['target'])."',
                        `shown`     = '".convert::ToInt($_POST['sichtbar'])."',
                        `internal`  = '".convert::ToInt($_POST['internal'])."',
                        `wichtig`   = '".convert::ToInt($_POST['wichtig'])."'
                    WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_navi_edited,"?admin=navi");
    break;

    case 'menu':
        $posi = db("UPDATE ".dba::get('navi')."
                    SET `shown`     = '".convert::ToInt($_GET['set'])."'
                    WHERE id = '".convert::ToInt($_GET['id'])."'");

        header("Location: ?admin=navi");
    break;

    case 'intern':
        $posi = db("UPDATE ".dba::get('navi_kats')."
                    SET `intern` = '".convert::ToInt($_GET['set'])."'
                    WHERE id = '".convert::ToInt($_GET['id'])."'");

        header("Location: ?admin=navi");
    break;

    case 'editlink':
        $get = _fetch(db("SELECT * FROM ".dba::get('navi_kats')." WHERE `id` = '".convert::ToInt($_GET['id'])."'"));

        $show = show($dir."/form_navi_kats", array("head" => _menu_edit_kat,
                "name" => _sponsors_admin_name,
                "placeholder" => _placeholder,
                "visible" => _menu_visible,
                "what" => _menu_edit_kat,
                "menu_kat_info" => _menu_kat_info,
                "n_name" => re($get['name']),
                "n_placeholder" => str_replace('nav_', '', re($get['placeholder'])),
                "sel_user" => ($get['level'] == 1 ? ' selected="selected"' : ''),
                "sel_trial" => ($get['level'] == 2 ? ' selected="selected"' : ''),
                "sel_member" => ($get['level'] == 3 ? ' selected="selected"' : ''),
                "sel_admin" => ($get['level'] == 4 ? ' selected="selected"' : ''),
                "guest" => _status_unregged,
                "user" => _status_user,
                "trial" => _status_trial,
                "member" => _status_member,
                "admin" => _status_admin,
                "do" => 'updatekat&amp;id='.$get['id']
        ));
    break;

    case 'updatekat':
        db("UPDATE ".dba::get('navi_kats')."
            SET `name`        = '".up($_POST['name'])."',
                `placeholder` = 'nav_".up($_POST['placeholder'])."',
                `level`       = '".convert::ToInt($_POST['level'])."'
            WHERE `id` = '".convert::ToInt($_GET['id'])."'");

        $show = info(_menukat_updated, '?admin=navi');
    break;

    case 'deletekat':
        db("DELETE FROM ".dba::get('navi_kats')." WHERE `id` = '".convert::ToInt($_GET['id'])."'");
        $show = info(_menukat_deleted, '?admin=navi');
    break;

    case 'addkat':
        $get = _fetch(db("SELECT * FROM ".dba::get('navi_kats')." WHERE `id` = '".convert::ToInt($_GET['id'])."'"));

        $show = show($dir."/form_navi_kats", array("head" => _menu_add_kat,
                "name" => _sponsors_admin_name,
                "placeholder" => _placeholder,
                "visible" => _menu_visible,
                "menu_kat_info" => _menu_kat_info,
                "what" => _menu_add_kat,
                "n_name" => "",
                "n_placeholder" => "",
                "sel_user" => "",
                "sel_trial" => "",
                "sel_member" => "",
                "sel_admin" => "",
                "guest" => _status_unregged,
                "user" => _status_user,
                "trial" => _status_trial,
                "member" => _status_member,
                "admin" => _status_admin,
                "do" => 'insertkat'
        ));
    break;

    case 'insertkat':
        db("INSERT INTO ".dba::get('navi_kats')."
            SET `name`        = '".up($_POST['name'])."',
                `placeholder` = 'nav_".up($_POST['placeholder'])."',
                `level`       = '".convert::ToInt($_POST['intern'])."'");

        $show = info(_menukat_inserted, '?admin=navi');
    break;

    default:
        //Links
        $qry = db("SELECT s1.*, s2.name AS katname FROM ".dba::get('navi')." AS s1 LEFT JOIN ".dba::get('navi_kats')." AS s2 ON s1.kat = s2.placeholder ORDER BY s2.name, s1.kat,s1.pos");
        $show_links = '';
        if(_rows($qry))
        {
            $color = 1;
            while($get = _fetch($qry))
            {
                if(!$get['type'])
                {
                    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=navi&amp;do=delete", "title" => _button_title_del, "del" => convSpace(_confirm_del_navi)));
                    $edit = "&nbsp;";
                    $type = _navi_space;
                }
                else
                {
                    $type = re($get['name']);
                    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=navi&amp;do=edit", "title" => _button_title_edit));
                    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=navi&amp;do=delete", "title" => _button_title_del, "del" => convSpace(_confirm_del_navi)));
                }

                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show_links .= show($dir."/navi_show", array("class" => $class,
                                                              "name" => $type,
                                                              "id" => $get['id'],
                                                              "set" => ($get['shown'] ? '0' : '1'),
                                                              "url" => cut($get['url'],34),
                                                              "kat" => re($get['katname']),
                                                              "shown" => ($get['shown'] ? _yesicon : _noicon),
                                                              "edit" => $edit,
                                                              "del" => $delete));
            } //while end
        }

        //Kats
        $qry = db("SELECT * FROM ".dba::get('navi_kats')." ORDER BY `name` ASC");
        $show_kats = '';
        if(_rows($qry))
        {
            $color = 1;
            while($get = _fetch($qry))
            {
                $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=navi&amp;do=editkat", "title" => _button_title_edit));
                $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=navi&amp;do=deletekat", "title" => _button_title_del, "del" => convSpace(_confirm_del_menu)));

                //Admin Link / No delete or edit
                if($get['placeholder'] == 'nav_admin')
                { $edit = ''; $delete = ''; }

                $class = ($color % 2) ? 'contentMainFirst' : 'contentMainSecond'; $color++;
                $show_kats .= show($dir."/navi_kats", array("name" => re($get['name']),
                                                            "id" => $get['id'],
                                                            "placeholder" => str_replace('nav_', '', re($get['placeholder'])),
                                                            "class" => $class,
                                                            "edit" => $edit,
                                                            "del" => $delete));
            } //while end
        }

        $show = show($dir."/navi", array("show_links" => $show_links, "show_kats" => $show_kats));
    break;
}
