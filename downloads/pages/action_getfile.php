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
    if(settings("reg_dl") == "1" && $chkMe == "unlogged")
        $index = error(_error_unregistered);
    else
    {
        $get = db("SELECT url,id FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);

        if(count_clicks('download',$get['id']))
            db("UPDATE ".dba::get('downloads')." SET `hits` = hits+1, `last_dl` = '".time()."' WHERE id = '".$get['id']."'");

        header("Location: ".$get['url']);
    }
}