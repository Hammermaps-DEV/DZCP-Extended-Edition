<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if($chkMe == "unlogged" || $chkMe < 2 && !permission('clankasse'))
        $index = error(_error_wrong_permissions);
    else
    {
        $has_permission = permission("clankasse");
        $entrys = cnt(dba::get('clankasse'));
        $qry = db("SELECT * FROM ".dba::get('clankasse')." ORDER BY datum DESC LIMIT ".($page - 1)*($maxclankasse = config('m_clankasse')).",".$maxclankasse."");

        $show = ''; $color = 1;
        while ($get = _fetch($qry))
        {
            $betrag = $get['betrag'];
            $betrag = str_replace(".",",",$betrag);

            if($get['pm'] == "0")
                $pm = show(_clankasse_plus, array("betrag" => $betrag,"w" => $w));
            else
                $pm = show(_clankasse_minus, array("betrag" => $betrag,"w" => $w));

            $edit = show("page/button_edit_single", array("id" => $get['id'], "title" => _button_title_edit, "action" => "action=admin&amp;do=edit"));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "title" => _button_title_delete, "action" => "action=admin&amp;do=delete", "del" => _confirm_del_entry));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/clankasse_show", array("betrag" => $pm,
                    "id" => $get['id'],
                    "class" => $class,
                    "for" => string::decode($get['member']),
                    "transaktion" => string::decode($get['transaktion']),
                    "delete" => $delete,
                    "edit" => $edit,
                    "datum" => date("d.m.Y",$get['datum'])));
        }

        $getp = db("SELECT sum(betrag) AS gesamt FROM ".dba::get('clankasse')." WHERE pm = 0",false,true);
        $getc = db("SELECT sum(betrag) AS gesamt FROM ".dba::get('clankasse')." WHERE pm = 1",false,true);

        $ges = $getp['gesamt'] - $getc['gesamt'];
        $ges = @round($ges,2);
        $ges = str_replace(".",",",$ges);

        if($getp['gesamt'] < $getc['gesamt'])
            $gesamt = show(_clankasse_summe_minus, array("summe" => $ges, "w" => $w));
        else
            $gesamt = show(_clankasse_summe_plus, array("summe" => $ges, "w" => $w));

        $new = ($has_permission ? _clankasse_new : '');

        $qrys = db("SELECT tbl1.id,tbl1.nick,tbl2.user,tbl2.payed
               FROM ".dba::get('users')." AS tbl1
               LEFT JOIN ".dba::get('c_payed')." AS tbl2 ON tbl2.user = tbl1.id
               WHERE tbl1.listck = '1'
               OR tbl1.level = '4'
               ORDER BY tbl1.nick");

        $showstatus = ''; $color = 1;
        while($gets = _fetch($qrys))
        {
            if($gets['user'])
            {
                if(paycheck($gets['payed']))
                    $status = show(_clankasse_status_payed, array("payed" => date("d.m.Y", $gets['payed'])));
                else if(date("d.m.Y", $gets['payed']) == date("d.m.Y", time()))
                    $status = show(_clankasse_status_today);
                else
                    $status = show(_clankasse_status_notpayed, array("payed" => date("d.m.Y", $gets['payed'])));
            }
            else
                $status = show(_clankasse_status_noentry);

            $edit = ($has_permission ? show(_admin_ck_edit, array("id" => $gets['id'])) : '');
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $showstatus .= show($dir."/status", array("nick" => autor($gets['id']),
                    "status" => $status,
                    "class" => $class,
                    "edit" => $edit));
        }

        unset($getp,$getc);
        $get = db("SELECT k_inhaber,k_nr,k_blz,k_bank,iban,bic,k_waehrung,k_vwz FROM ".dba::get('settings'),false,true);
        $seiten = nav($entrys,$maxclankasse,"?action=nav");
        $index = show($dir."/clankasse", array("show" => $show,
                "showstatus" => $showstatus,
                "clankasse_head" => _clankasse_head,
                "server_head" => _clankasse_server_head,
                "kinhaber" => _clankasse_inhaber,
                "knr" => _clankasse_nr,
                "kblz" => _clankasse_blz,
                "kbank" => _clankasse_bank,
                "kvwz" => _clankasse_vwz,
                "cfor" => _clankasse_for,
                "cdatum" => _datum,
                "ctransaktion" => _clankasse_ctransaktion,
                "cbetrag" => _clankasse_cbetrag,
                "cakt" => _clankasse_cakt,
                "edit" => _editicon_blank,
                "delete" => _deleteicon_blank,
                "didpayed" => _clankasse_didpayed,
                "nick" => _nick,
                "status" => _clankasse_status_status,
                "inhaber" => $get['k_inhaber'],
                "kontonr" => $get['k_nr'],
                "new" => $new,
                "blz" => $get['k_blz'],
                "iban" => $get['iban'],
                "bic" => $get['bic'],
                "bank" => $get['k_bank'],
                "vwz" => $get['k_vwz'],
                "summe" => $gesamt,
                "seiten" => $seiten));
    }
}