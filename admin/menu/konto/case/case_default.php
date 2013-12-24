<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = settings::get_array(array('k_inhaber','k_nr','k_blz','k_bank','iban','bic','k_waehrung','k_vwz'));
$waehrung = string::decode($get['k_waehrung']);
$waehrung_list = _select_field_waehrung;
$waehrung_list = str_replace('<option value="'.$waehrung.'">","<option value="'.$waehrung.'" selected="selected">', $waehrung_list);
$konto_show = show($dir."/form_konto", array("inhaber" => string::decode($get['k_inhaber']),
                                             "kontonr" => $get['k_nr'],
                                             "waehrung" => $waehrung_list,
                                             "blz" => $get['k_blz'],
                                             "bank" => string::decode($get['k_bank']),
                                             "vwz" => string::decode($get['k_vwz']),
                                             "iban" => string::decode($get['iban']),
                                             "bic" => string::decode($get['bic'])));

$konto = show($dir."/form", array("head" => _config_konto_head,"what" => "konto", "top" => _config_c_clankasse, "value" => _button_value_save, "show" => $konto_show));

$qryk = db("SELECT id,kat FROM ".dba::get('c_kats')); $color = 1; $show = '';
while($getk = _fetch($qryk))
{
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $edit = show("page/button_edit_single", array("id" => $getk['id'], "action" => "index=admin&amp;admin=konto&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $getk['id'], "action" => "index=admin&amp;admin=konto&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
    $show .= show($dir."/clankasse_show", array("name" => string::decode($getk['kat']), "class" => $class, "edit" => $edit, "delete" => $delete));
}

$show = show($dir."/clankasse", array("show" => $show, "konto" => $konto));