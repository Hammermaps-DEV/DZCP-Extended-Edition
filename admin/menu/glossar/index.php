<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/* Admin Menu-File */
if(_adminMenu != 'true')
    exit();

$where = $where.': '._server_admin_head;
bbcode::use_glossar(false);

switch ($do)
{
    case 'add':
        $show = show($dir."/form_glossar", array("head" => _admin_glossar_add,
                "link" => _glossar_bez,
                "beschreibung" => _glossar_erkl,
                "llink" => "",
                "lbeschreibung" => "",
                "do" => "insert",
                "value" => _button_value_add
        ));
    break;
    case 'insert':
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || preg_match("#[[:punct:]]]#is",$_POST['link']) === true)
        {
            if(empty($_POST['link']))       $show = error(_admin_error_glossar_word);
            elseif($_POST['beschreibung'])  $show = error(_admin_error_glossar_desc);
            elseif(preg_match("#[[:punct:]]#is",$_POST['link'])) $show = error(_glossar_specialchar);
        }
        else
        {
            db("INSERT INTO ".dba::get('glossar')." SET `word` = '".string::encode($_POST['link'])."', `glossar` = '".string::encode($_POST['beschreibung'])."'");
            $show = info(_admin_glossar_added,'?index=admin&amp;admin=glossar');
        }
    break;
    case 'edit':
        $get = db("SELECT * FROM ".dba::get('glossar')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        $show = show($dir."/form_glossar", array("head" => _admin_glossar_add,
                "link" => _glossar_bez,
                "beschreibung" => _glossar_erkl,
                "llink" => string::decode($get['word']),
                "lbeschreibung" => string::decode($get['glossar']),
                "do" => "update&amp;id=".$_GET['id'],
                "value" => _button_value_edit
        ));
    break;
    case 'update':
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || preg_match("#[[:punct:]]]#is",$_POST['link']) === true)
        {
            if(empty($_POST['link']))       $show = error(_admin_error_glossar_word);
            elseif($_POST['beschreibung'])  $show = error(_admin_error_glossar_desc);
            elseif(preg_match("#[[:punct:]]#is",$_POST['link'])) $show = error(_glossar_specialchar);
        }
        else
        {
            db("UPDATE ".dba::get('glossar')." SET `word`    = '".string::encode($_POST['link'])."', `glossar` = '".string::encode($_POST['beschreibung'])."' WHERE id = '".convert::ToInt($_GET['id'])."'");
            $show = info(_admin_glossar_edited,'?index=admin&amp;admin=glossar');
        }
    break;
    case 'delete':
        $del = db("DELETE FROM ".dba::get('glossar')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_admin_glossar_deleted,'?index=admin&amp;admin=glossar');
    break;
    default:
        $maxglossar = 20;
        $entrys = cnt(dba::get('glossar'));
        $qry = db("SELECT * FROM ".dba::get('glossar')." ORDER BY word LIMIT ".($page - 1)*$maxglossar.",".$maxglossar.""); $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'],
                    "action" => "index=admin&amp;admin=glossar&amp;do=edit",
                    "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'],
                    "action" => "index=admin&amp;admin=glossar&amp;do=delete",
                    "title" => _button_title_del,
                    "del" => _confirm_del_entry));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $show .= show($dir."/glossar_show", array("word" => string::decode($get['word']),
                    "class" => $class,
                    "edit" => $edit,
                    "delete" => $delete,
                    "glossar" => bbcode::parse_html($get['glossar'])));
        }

        $show = show($dir."/glossar", array("head" => _glossar_head,
                "word" => _glossar_bez,
                "bez" => _glossar_erkl,
                "show" => $show,
                "cnt" => $entrys,
                "nav" => nav($entrys,$maxglossar,"?index=admin&amp;admin=glossar"),
                "add" => _admin_glossar_add
        ));
    break;
}
