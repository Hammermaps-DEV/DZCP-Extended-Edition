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
    $get = db("SELECT url,id,hits FROM ".dba::get('links')." WHERE `id` = '".convert::ToInt($_GET['id'])."'",false,true);

    if(count_clicks('link',$get['id']))
        db("UPDATE ".dba::get('links')." SET `hits` = ".($get['hits'] + 1)." WHERE `id` = '".$get['id']."'");

    header("Location: ".links(string::decode($get['url'])));
}