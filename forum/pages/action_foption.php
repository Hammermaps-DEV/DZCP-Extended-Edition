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
    if($_GET['do'] == "fabo")
    {
        if(isset($_POST['f_abo']))
        {
            $f_abo = db("INSERT INTO ".$db['f_abo']."
                    SET `user` = '".((int)$userid)."',
                        `fid`  = '".intval($_GET['id'])."',
                        `datum`  = '".((int)time())."'");
        } else {
            $f_abo = db("DELETE FROM ".$db['f_abo']."
                   WHERE user = '".((int)$userid)."'
                   AND fid = '".intval($_GET['id'])."'");
        }
        $index = info(_forum_fabo_do, "?action=showthread&amp;id=".$_GET['id']."");
    }
}
?>