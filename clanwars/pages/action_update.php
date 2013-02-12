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
    if($chkMe == "unlogged")
    {
        $index = error(_error_have_to_be_logged, 1);
    } else {
        $qry = db("SELECT * FROM ".$db['cw_player']."
               WHERE cwid = '".convert::ToInt($_GET['id'])."'
               AND member = '".convert::ToInt($userid)."'");
        if(_rows($qry))
        {
            $upd = db("UPDATE ".$db['cw_player']."
                 SET `status` = '".convert::ToInt($_POST['status'])."'
                 WHERE cwid = '".convert::ToInt($_GET['id'])."'
                 AND member = '".convert::ToInt($userid)."'");
        } else {
            $ins = db("INSERT INTO ".$db['cw_player']."
                 SET `cwid`   = '".convert::ToInt($_GET['id'])."',
                     `member` = '".convert::ToInt($userid)."',
                     `status` = '".convert::ToInt($_POST['status'])."'");
        }

        $index = info(_cw_status_set, "?action=details&amp;id=".$_GET['id']."");
    }
}
?>