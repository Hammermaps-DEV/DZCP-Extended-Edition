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
    if(permission("clankasse"))
    {
        if ($_GET['do'] == "new")
        {
            $qry = db("SELECT * FROM ".$db['c_kats']."");
            while($get = _fetch($qry))
            {
                $trans .= show(_select_field, array("value" => re($get['kat']),
                        "sel" => "",
                        "what" => re($get['kat'])));
            }

            $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                    "month" => dropdown("month",date("m",time())),
                    "year" => dropdown("year",date("Y",time()))));

            $index = show($dir."/new", array("newhead" => _clankasse_head_new,
                    "betrag" => _clankasse_cbetrag,
                    "datum" => _datum,
                    "vonan" => _clankasse_for,
                    "thisyear" => date("Y"),
                    "beitrag" => _clankasse_sbeitrag,
                    "miete" => _clankasse_smiete,
                    "value" => _button_value_add,
                    "dropdown_date" => $dropdown_date,
                    "ssonstiges" => _clankasse_ssonstiges,
                    "einzahlung" => _clankasse_einzahlung,
                    "auszahlung" => _clankasse_auszahlung,
                    "trans" => $trans,
                    "sponsor" => _clankasse_ssponsor,
                    "sonstiges" => _clankasse_sonstiges,
                    "member" => _member,
                    "transaktion" => _clankasse_ctransaktion,
                    "minus" => _clankasse_admin_minus,
                    "post" => time()));

        } elseif ($_GET['do'] == "add") {
            if(!$_POST['t'] OR !$_POST['m'])
            {
                $index = error(_error_clankasse_empty_datum, 1);
            } elseif($_POST['transaktion'] == "lazy") {
                $index = error(_error_clankasse_empty_transaktion, 1);
            } elseif(!$_POST['betrag']) {
                $index = error(_error_clankasse_empty_betrag, 1);
            } else {
                $betrag = $_POST['betrag'];
                $betrag = preg_replace("#,#iUs",".",$betrag);
                $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

                $qry = db("INSERT INTO ".$db['clankasse']."
                   SET `datum`        = '".convert::ToInt($datum)."',
                       `member`       = '".$_POST['member']."',
                       `transaktion`  = '".up($_POST['transaktion'])."',
                       `pm`           = '".convert::ToInt($_POST['pm'])."',
                       `betrag`       = '".up($betrag)."'");

                $index = info(_clankasse_saved, "../clankasse/");
            }
        } elseif ($_GET['do'] == "delete" && $_GET['id']) {
            $qry = db("DELETE FROM ".$db['clankasse']."
                 WHERE id = ".convert::ToInt($_GET['id']));

            $index = info(_clankasse_deleted, "../clankasse/");
        } elseif ($_GET['do'] == "update" && $_POST['id']) {
            if(!$_POST['datum'])
            {
                $index = error(_error_clankasse_empty_datum, 1);
            } elseif(!$_POST['betrag']) {
                $index = error(_error_clankasse_empty_betrag, 1);
            } elseif(!$_POST['transaktion']) {
                $index = error(_error_clankasse_empty_transaktion, 1);
            } else {
                $res = db("UPDATE ".$db['clankasse']."
                     SET `datum`        = '".convert::ToInt($_POST['datum'])."',
                         `transaktion`  = '".up($_POST['transaktion'])."',
                         `pm`           = '".convert::ToInt($_POST['pm'])."',
                         `betrag`       = '".up($_POST['betrag'])."'
                     WHERE id = ".convert::ToInt($_POST['id']));

                $index = info(_clankasse_edited, "../clankasse/");
            }
        } elseif ($_GET['do'] == "edit") {
            $qry = db("SELECT * FROM ".$db['clankasse']."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
            $get = _fetch($qry);

            $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
                    "month" => dropdown("month",date("m",$get['datum'])),
                    "year" => dropdown("year",date("Y",$get['datum']))));

            if($get['pm'] == "0") $psel = "selected=\"selected\"";
            else $msel = "selected=\"selected\"";

            $qryk = db("SELECT * FROM ".$db['c_kats']."");
            while($getk = _fetch($qryk))
            {
                if($getk['kat'] == $get['transaktion']) $sel = "selected=\"selected\"";
                else $sel = "";

                $trans .= show(_select_field, array("value" => re($getk['kat']),
                        "sel" => $sel,
                        "what" => re($getk['kat'])));
            }
            $index = show($dir."/edit", array("newhead" => _clankasse_head_edit,
                    "betrag" => _clankasse_cbetrag,
                    "datum" => _datum,
                    "vonan" => _clankasse_for,
                    "dropdown_date" => $dropdown_date,
                    "id" => $_GET['id'],
                    "psel" => $psel,
                    "msel" => $msel,
                    "value" => _button_value_edit,
                    "bsel" => $bsel,
                    "misel" => $misel,
                    "ssel" => $ssel,
                    "spsel" => $spsel,
                    "trans" => $trans,
                    "evonan" => re($get['member']),
                    "sum" => re($get['betrag']),
                    "beitrag" => _clankasse_sbeitrag,
                    "miete" => _clankasse_smiete,
                    "ssonstiges" => _clankasse_ssonstiges,
                    "einzahlung" => _clankasse_einzahlung,
                    "auszahlung" => _clankasse_auszahlung,
                    "sponsor" => _clankasse_ssponsor,
                    "sonstiges" => _clankasse_sonstiges,
                    "member" => _member,
                    "transaktion" => _clankasse_ctransaktion,
                    "minus" => _clankasse_admin_minus,
                    "post" => time()));
        } elseif($_GET['do'] == "editck") {
            if(!$_POST['t'] OR !$_POST['m'])
            {
                $index = error(_error_clankasse_empty_datum, 1);
            } elseif($_POST['transaktion'] == "lazy") {
                $index = error(_error_clankasse_empty_transaktion, 1);
            } elseif(!$_POST['betrag']) {
                $index = error(_error_clankasse_empty_betrag, 1);
            } else {
                $betrag = $_POST['betrag'];
                $betrag = preg_replace("#,#iUs",".",$betrag);
                $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

                $qry = db("UPDATE ".$db['clankasse']."
                   SET `datum`        = '".convert::ToInt($datum)."',
                       `member`       = '".up($_POST['member'])."',
                       `transaktion`  = '".up($_POST['transaktion'])."',
                       `pm`           = '".convert::ToInt($_POST['pm'])."',
                       `betrag`       = '".up($betrag)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

                $index = info(_clankasse_edited, "../clankasse/");
            }
        } elseif($_GET['do'] == "paycheck") {
            $qry = db("SELECT payed FROM ".$db['c_payed']."
                 WHERE user = '".convert::ToInt($_GET['id'])."'");
            $get = _fetch($qry);

            if(_rows($qry))
            {
                $tag = date("d", $get['payed']);
                $monat = date("m", $get['payed']);
                $jahr = date("Y", $get['payed']);
            } else {
                $tag = date("d", time());
                $monat = date("m", time());
                $jahr = date("Y", time());
            }
            $index = show($dir."/paycheck", array("id" => $_GET['id'],
                    "head" => _clankasse_edit_paycheck,
                    "user" => _user,
                    "value" => _button_value_edit,
                    "payed_till" => _clankasse_payed_till,
                    "puser" => autor($_GET['id']),
                    "t" => $tag,
                    "m" => $monat,
                    "j" => $jahr));
        } elseif($_GET['do'] == "editpaycheck") {
            $qry = db("SELECT payed FROM ".$db['c_payed']."
                 WHERE user = '".convert::ToInt($_GET['id'])."'");

            $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
            if(_rows($qry))
            {
                $update = db("UPDATE ".$db['c_payed']."
                      SET `payed` = '".convert::ToInt($datum)."'
                      WHERE user = '".convert::ToInt($_GET['id'])."'");
            } else {
                $insert = db("INSERT INTO ".$db['c_payed']."
                      SET `user`  = '".convert::ToInt($_GET['id'])."',
                          `payed` = '".convert::ToInt($datum)."'");
            }
            $index = info(_info_clankass_status_edited, "../clankasse/");
        }
    } else {
        $index = error(_error_wrong_permissions, 1);
    }
}
?>