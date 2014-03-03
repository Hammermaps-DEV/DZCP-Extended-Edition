<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
    $inhalt = bbcode::parse_html($_POST['inhalt']);
    $index = show($dir."/sites", array("titel" => string::decode($_POST['titel']),
            "inhalt" => $inhalt));

    echo convert::UTF8('<table class="mainContent" cellspacing="1"'.$index.'</table>');
    exit;
}