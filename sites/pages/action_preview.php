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
    header("Content-type: text/html; charset=utf-8");
    if($_POST['html'] == "1") $inhalt = bbcode_html($_POST['inhalt'],1);
    else $inhalt = bbcode($_POST['inhalt'],1);

    $index = show($dir."/sites", array("titel" => re($_POST['titel']),
            "inhalt" => $inhalt));

    echo '<table class="mainContent" cellspacing="1"'.$index.'</table>';
    exit;
}