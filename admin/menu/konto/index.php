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

$where = $where.': '._config_clankasse_head;
switch ($do)
{
    case 'update':
        db("UPDATE ".dba::get('settings')."
                   SET `k_inhaber`    = '".string::encode($_POST['inhaber'])."',
                       `k_nr`         = '".string::encode($_POST['kontonr'])."',
                       `k_waehrung`   = '".string::encode($_POST['waehrung'])."',
                       `k_bank`       = '".string::encode($_POST['bank'])."',
                       `k_blz`        = '".string::encode($_POST['blz'])."',
                       `k_vwz`        = '".string::encode($_POST['vwz'])."',
                       `iban`         = '".string::encode($_POST['iban'])."',
                       `bic`          = '".string::encode($_POST['bic'])."'
                   WHERE id = 1");
        $show = info(_config_set, "?admin=konto");
    break;
    case 'add':
        if(empty($_POST['kat']))
            $show = error(_clankasse_empty_kat);
        else
        {
            db("INSERT INTO ".dba::get('c_kats')." SET `kat` = '".string::encode($_POST['kat'])."'");
            $show = info(_clankasse_kat_added, "?admin=konto");
        }
    break;
    case 'edit':
        $get = db("SELECT id,kat FROM ".dba::get('c_kats')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        $show = show($dir."/form_clankasse_edit", array( "do" => "editkat&amp;id=".$_GET['id']."", "kat" => string::decode($get['kat'])));
    break;
    case 'editkat':
        if(empty($_POST['kat']))
            $show = error(_clankasse_empty_kat);
        else
        {
            db("UPDATE ".dba::get('c_kats')." SET `kat` = '".string::encode($_POST['kat'])."' WHERE id = '".convert::ToInt($_GET['id'])."'");
            $show = info(_clankasse_kat_edited, "?admin=konto");
        }
    break;
    case 'delete':
        db("DELETE FROM ".dba::get('c_kats')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_clankasse_kat_deleted, "?admin=konto");
    break;
    case 'new':
        $show = show($dir."/form_clankasse", array());
    break;
    default:
        $get = db("SELECT k_inhaber,k_nr,k_blz,k_bank,iban,bic,k_waehrung,k_vwz FROM ".dba::get('settings'),false,true);
        $waehrung = string::decode($get['k_waehrung']);
        $waehrung_list = _select_field_waehrung;
        $waehrung_list = str_replace('<option value="'.$waehrung.'">"','"<option value="'.$waehrung.'" selected="selected">', $waehrung_list);
        $konto_show = show($dir."/form_konto", array("inhaber" => string::decode($get['k_inhaber']),
                                                     "kontonr" => $get['k_nr'],
                                                     "waehrung" => $waehrung_list,
                                                     "blz" => $get['k_blz'],
                                                     "bank" => string::decode($get['k_bank']),
                                                     "vwz" => string::decode($get['k_vwz']),
                                                     "iban" => string::decode($get['iban']),
                                                     "bic" => string::decode($get['bic'])));

        $konto = show($dir."/form", array("head" => _config_konto_head,"what" => "konto", "top" => _config_c_clankasse, "value" => _button_value_save, "show" => $konto_show));

        $qryk = db("SELECT id,kat FROM ".dba::get('c_kats')); $color = 0; $show = '';
        while($getk = _fetch($qryk))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $edit = show("page/button_edit_single", array("id" => $getk['id'], "action" => "admin=konto&amp;do=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $getk['id'], "action" => "admin=konto&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
            $show .= show($dir."/clankasse_show", array("name" => string::decode($getk['kat']), "class" => $class, "edit" => $edit, "delete" => $delete));
        }

        $show = show($dir."/clankasse", array("show" => $show, "konto" => $konto));
    break;
}
