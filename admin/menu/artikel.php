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

$where = $where.': '._artikel;
wysiwyg::set('advanced');

switch($do)
{
    case 'add':
        $error = '';
        if($_POST)
        {
            if(empty($_POST['titel']) || empty($_POST['artikel']))
            {
                if(empty($_POST['titel']))
                    $error = show("errors/errortable", array("error" => _empty_artikel_title));
                else if(empty($_POST['artikel']))
                    $error = show("errors/errortable", array("error" => _empty_artikel));
            }
            else
            {
                db("INSERT INTO ".dba::get('artikel')."
                    SET `autor`  = '".convert::ToInt($userid)."',
                        `kat`    = '".convert::ToInt($_POST['kat'])."',
                        `titel`  = '".string::encode($_POST['titel'])."',
                        `text`   = '".string::encode($_POST['artikel'])."',
                        `comments` = '".convert::ToInt($_POST['comments'])."',
                        `link1`  = '".string::encode($_POST['link1'])."',
                        `link2`  = '".string::encode($_POST['link2'])."',
                        `link3`  = '".string::encode($_POST['link3'])."',
                        `url1`   = '".links($_POST['url1'])."',
                        `url2`   = '".links($_POST['url2'])."',
                        `url3`   = '".links($_POST['url3'])."'");

                $show = info(_artikel_added, "?admin=artikel");
            }
        }

        if(empty($show))
        {
            $qryk = db("SELECT * FROM ".dba::get('newskat').""); $kat = '';
            while($getk = _fetch($qryk))
            {
                $sel = ((isset($_POST['kat']) ? $_POST['kat'] : '0') == $getk['id'] ? 'selected="selected"' : '');
                $kat .= show(_select_field, array("value" => $getk['id'], "sel" => $sel, "what" => string::decode($getk['kategorie'])));
            }

            $selr_ac = ($_POST['comments'] ? 'selected="selected"' : '');
            $show = show($dir."/artikel_form", array("head" => _artikel_add,
                                                     "autor" => autor(convert::ToInt($userid)),
                                                     "kat" => $kat,
                                                     "do" => "add",
                                                     "selr_ac" => $selr_ac,
                                                     "error" => $error,
                                                     "titel" => (isset($_POST['titel']) ? string::decode($_POST['titel']) : ''),
                                                     "artikeltext" => (isset($_POST['artikel']) ? string::decode($_POST['artikel']) : ''),
                                                     "link1" => (isset($_POST['link1']) ? string::decode($_POST['link1']) : ''),
                                                     "link2" => (isset($_POST['link2']) ? string::decode($_POST['link2']) : ''),
                                                     "link3" => (isset($_POST['link3']) ? string::decode($_POST['link3']) : ''),
                                                     "url1" => (isset($_POST['url1']) ? $_POST['url1'] : ''),
                                                     "url2" => (isset($_POST['url2']) ? $_POST['url2'] : ''),
                                                     "url3" => (isset($_POST['url3']) ? $_POST['url3'] : ''),
                                                     "button" => _button_value_add));
        }
    break;

    case 'edit':
        if($_POST)
        {
            if(empty($_POST['titel']) || empty($_POST['artikel']))
            {
                if(empty($_POST['titel']))
                    $error = show("errors/errortable", array("error" => _empty_artikel_title));
                else if(empty($_POST['artikel']))
                    $error = show("errors/errortable", array("error" => _empty_artikel));
            }
            else
            {
                db("UPDATE ".dba::get('artikel')."
                    SET `kat`    = '".convert::ToInt($_POST['kat'])."',
                        `titel`  = '".string::encode($_POST['titel'])."',
                        `text`   = '".string::encode($_POST['artikel'])."',
                        `comments` = '".convert::ToInt($_POST['comments'])."',
                        `link1`  = '".string::encode($_POST['link1'])."',
                        `link2`  = '".string::encode($_POST['link2'])."',
                        `link3`  = '".string::encode($_POST['link3'])."',
                        `url1`   = '".links($_POST['url1'])."',
                        `url2`   = '".links($_POST['url2'])."',
                        `url3`   = '".links($_POST['url3'])."'
                    WHERE id = '".convert::ToInt($_GET['id'])."'");

                $show = info(_artikel_edited, "?admin=artikel");
            }
        }

        if(empty($show))
        {
            $get = db("SELECT * FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            $qryk = db("SELECT * FROM ".dba::get('newskat').""); $kat = '';
            while($getk = _fetch($qryk))
            {
                $sel = ((isset($_POST['kat']) ? $_POST['kat'] : $get['kat']) == $getk['id'] ? 'selected="selected"' : '');
                $kat .= show(_select_field, array("value" => $getk['id'], "sel" => $sel, "what" => string::decode($getk['kategorie'])));
            }

            $do = show(_artikel_edit_link, array("id" => $_GET['id']));
            $selr_ac = ($get['comments'] ? 'selected="selected"' : '');
            $show = show($dir."/artikel_form", array("head" => _artikel_edit,
                                                     "autor" => autor(convert::ToInt($userid)),
                                                     "kat" => $kat,
                                                     "do" => $do,
                                                     "artikeltext" => (isset($_POST['artikel']) ? string::decode($_POST['artikel']) : string::decode($get['text'])),
                                                     "titel" => (isset($_POST['titel']) ? string::decode($_POST['titel']) : string::decode($get['titel'])),
                                                     "link1" => (isset($_POST['link1']) ? string::decode($_POST['link1']) : string::decode($get['link1'])),
                                                     "link2" => (isset($_POST['link2']) ? string::decode($_POST['link2']) : string::decode($get['link2'])),
                                                     "link3" => (isset($_POST['link3']) ? string::decode($_POST['link3']) : string::decode($get['link3'])),
                                                     "url1" => (isset($_POST['url1']) ? $_POST['url1'] : $get['url1']),
                                                     "url2" => (isset($_POST['url2']) ? $_POST['url2'] : $get['url2']),
                                                     "url3" => (isset($_POST['url3']) ? $_POST['url3'] : $get['url3']),
                                                     "selr_ac" => $selr_ac,
                                                     "error" => "",
                                                     "button" => _button_value_edit));
        }
    break;

    case 'editartikel':
    break;

    case 'delete':
        $qry = db("DELETE FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_artikel_deleted, "?admin=artikel");
    break;

    case 'public':
        $get = db("SELECT public,id FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        db("UPDATE ".dba::get('artikel')." SET `public` = '".($get['public'] ? '0' : '1')."', `datum`  = '".($get['public'] ? '0' : time())."' WHERE id = '".$get['id']."'");
        header("Location: ?admin=artikel");
    break;

    default:
        $entrys = cnt(dba::get('artikel')); $show = ''; $color = 1;
        $qry = db("SELECT * FROM ".dba::get('artikel')." ORDER BY `public` ASC, `datum` DESC LIMIT ".($page - 1)*($maxadminartikel=config('m_adminartikel')).",".$maxadminartikel."");
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "admin=artikel&amp;do=edit","title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "admin=artikel&amp;do=delete","title" => _button_title_del,"del" => _confirm_del_artikel));
            $titel = show(_artikel_show_link, array("titel" => string::decode(cut($get['titel'],config('l_newsadmin'))),"id" => $get['id']));
            $public = '<a href="?admin=artikel&amp;do=public&amp;id='.$get['id'].'"><img src="../inc/images/'.($get['public'] ? 'public.gif' : 'nonpublic.gif').'" alt="" title="'._public.'" /></a>';
            $datum = (empty($get['datum']) ? _no_public : date("d.m.y H:i", $get['datum'])._uhr);
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/admin_show_artikel", array("date" => $datum,
                                                            "titel" => $titel,
                                                            "class" => $class,
                                                            "autor" => autor($get['autor']),
                                                            "public" => $public,
                                                            "edit" => $edit,
                                                            "delete" => $delete));
        }

        $nav = nav($entrys,config('m_adminnews'),"?admin=artikel");
        $show = show($dir."/admin_artikel", array("nav" => $nav,"show" => $show));
    break;
}
