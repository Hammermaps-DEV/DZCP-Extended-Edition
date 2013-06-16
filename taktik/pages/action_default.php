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
    if($chkMe == "unlogged" || $chkMe < 2 && !permission('edittactics'))
        $index = error(_error_wrong_permissions);
    else
    {
        $qry = db("SELECT id,datum,map,spart,sparct,standardt,standardct,autor FROM ".dba::get('taktik')." ORDER BY id DESC");
        $color = 1; $show = '';
        while ($get = _fetch($qry))
        {
            $sparct = (!empty($get['sparct']) ? show(_taktik_spar_ct, array("id" => $get['id'])) : '');
            $spart = (!empty($get['spart']) ? show(_taktik_spar_t, array("id" => $get['id'])) : '');
            $standardct = (!empty($get['standardct']) ? show(_taktik_standard_ct, array("id" => $get['id'])) : '');
            $standardt = (!empty($get['standardt']) ? show(_taktik_standard_t, array("id" => $get['id'])) : '');

            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=do&amp;what=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "action=do&amp;what=delete", "title" => _button_title_del, "del" => convSpace(_confirm_del_taktik)));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/taktiken_show", array("map" => re($get['map']),
                    "id" => $get['id'],
                    "class" => $class,
                    "standard_t" => $standardt,
                    "standard_ct" => $standardct,
                    "spar_t" => $spart,
                    "edit" => $edit,
                    "delete" => $delete,
                    "spar_ct" => $sparct,
                    "autor" => autor($get['autor'])));
        }

        $index = show($dir."/taktiken", array("show" => $show,
                "taktik_head" => _taktik_head,
                "new_taktik" => _taktik_new,
                "upload" => _taktik_upload,
                "map" => _map,
                "edit" => _editicon_blank,
                "delete" => _deleteicon_blank,
                "t" => _taktik_t,
                "ct" => _taktik_ct,
                "autor" => _autor));
    }
}