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
    $qry = db("SELECT s1.*,s2.internal FROM ".dba::get('sites')." AS s1
             LEFT JOIN ".dba::get('navi')." AS s2
             ON s1.id = s2.editor
             WHERE s1.id = '".convert::ToInt($_GET['show'])."'");

    if(_rows($qry))
    {
        $get = _fetch($qry);
        if($get['internal'] == 1 && ($chkMe == 1 || $chkMe == "unlogged"))
            $index = error(_error_wrong_permissions);
        else
        {
            $where = re($get['titel']);
            $title = $pagetitle." - ".$where."";
            $inhalt = ($get['html'] ? bbcode_html($get['text']) : bbcode($get['text']));
            $index = show($dir."/sites", array("titel" => re($get['titel']), "inhalt" => $inhalt));
        }
    }
    else
        $index = error(_sites_not_available);
}