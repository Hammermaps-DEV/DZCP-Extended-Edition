<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $get = db("SELECT url FROM ".dba::get('linkus')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    header("Location: ".$get['url']);
}