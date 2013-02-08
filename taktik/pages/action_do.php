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
    if(!permission("edittactics"))
        $index = error(_error_wrong_permissions, 1);
    else
    {
        $wysiwyg = '_word';
        switch(isset($_GET['what']) ? $_GET['what'] : '')
        {
            case 'new':
                $index = show($dir."/new", array("date" => date("d.m.Y"),
                "autor" => autor($userid),
                "tautor" => _autor,
                "value" => _button_value_add,
                "map" => _map,
                "choose" => _taktik_choose,
                "spar_ct" => _taktik_tspar_ct,
                "spar_t" => _taktik_tspar_t,
                "standard_t" => _taktik_tstandard_t,
                "standard_ct" => _taktik_tstandard_ct,
                "newtaktik_head" => _taktik_new_head));
                break;
            case 'add':
                if(!isset($_POST['map']) || empty($_POST['map']))
                    $index = error(_error_taktik_empty_map, 1);
                else
                {
                    db("INSERT INTO ".$db['taktik']." SET
                           `datum`      = '".((int)time())."',
                           `map`        = '".up($_POST['map'])."',
                           `spart`      = '".up($_POST['spart'], 1)."',
                           `sparct`     = '".up($_POST['sparct'], 1)."',
                           `standardt`  = '".up($_POST['standardt'], 1)."',
                           `standardct` = '".up($_POST['standardct'], 1)."',
                           `autor`      = '".((int)$userid)."'");

                    $index = info(_taktik_added, "../taktik/");
                }
                break;
            case 'delete':
                if(isset($_GET['id']) && !empty($_GET['id']))
                {
                    db("DELETE FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']));
                    $index = info(_taktik_deleted, "../taktik/");
                }
                break;
            case 'edit':
                if(isset($_GET['id']) && !empty($_GET['id']))
                {
                    $get = db("SELECT * FROM ".$db['taktik']." WHERE id = ".intval($_GET['id']),false,true);
                    $index = show($dir."/edit", array("id" => $_GET['id'],
                            "map" => re($get['map']),
                            "autor" => autor($get['autor']),
                            "value" => _button_value_edit,
                            "tautor" => _autor,
                            "tmap" => _map,
                            "choose" => _taktik_choose,
                            "spar_tct" => _taktik_tspar_ct,
                            "spar_tt" => _taktik_tspar_t,
                            "standard_tt" => _taktik_tstandard_t,
                            "standard_tct" => _taktik_tstandard_ct,
                            "edit_head" => _taktik_edit_head,
                            "standard_t" => re($get['standardt']),
                            "standard_ct" => re($get['standardct']),
                            "spar_ct" => re($get['sparct']),
                            "spar_t" => re($get['spart'])));
                }
                break;
            case 'update':
                if(isset($_POST['id']) && !empty($_POST['id']))
                {
                    if(!isset($_POST['map']) || empty($_POST['map']))
                        $index = error(_error_taktik_empty_map, 1);
                    else
                    {
                        db("UPDATE ".$db['taktik']." SET
                               `map`        = '".up($_POST['map'])."',
                               `sparct`     = '".up($_POST['sparct'], 1)."',
                               `spart`      = '".up($_POST['spart'], 1)."',
                               `standardct` = '".up($_POST['standardct'], 1)."',
                               `standardt`  = '".up($_POST['standardt'], 1)."'
                                WHERE id = ".intval($_POST['id']));

                        $index = info(_error_taktik_edited, "../taktik/");
                    }
                }
                break;
        }
    }
}
?>