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
    if ($chkMe < 2)
        $index = error(_error_wrong_permissions, 1);
    else
    {
        if(isset($_GET['what']) && !empty($_GET['what']))
        {
            $get = db("SELECT * FROM ".dba::get('taktik')." WHERE id = ".convert::ToInt($_GET['id']),false,true);
            if($_GET['what'] == "ct")
            {
                $what = _taktik_tspar_ct;
                $show = bbcode($get['sparct']);
            }
            else if($_GET['what'] == "t")
            {
                $what = _taktik_tspar_t;
                $show = bbcode($get['spart']);
            }

            $posted = show(_taktik_posted, array("autor" => autor($get['autor']), "datum" => date("d.m.Y", $get['datum'])));
            $headline = show(_taktik_headline, array("what" => $what, "map" => re($get['map'])));
            $index = show($dir."/taktik", array("id" => $_GET['id'], "posted" => $posted, "headline" => $headline, "show" => $show));
        }
        else
            $index = info(_taktik_added, "../taktik/");
    }
}
?>