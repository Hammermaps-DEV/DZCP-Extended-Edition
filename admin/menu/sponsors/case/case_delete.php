<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$img_ext = array('gif','jpg','png');
foreach ($img_ext as $ext)
{
    if(file_exists(basePath."/banner/sponsors/site_".convert::ToInt($_GET['id']).".".$ext))
        @unlink(basePath."/banner/sponsors/site_".convert::ToInt($_GET['id']).".".$ext);

    if(file_exists(basePath."/banner/sponsors/banner_".convert::ToInt($_GET['id']).".".$ext))
        @unlink(basePath."/banner/sponsors/banner_".convert::ToInt($_GET['id']).".".$ext);

    if(file_exists(basePath."/banner/sponsors/box_".convert::ToInt($_GET['id']).".".$ext))
        @unlink(basePath."/banner/sponsors/box_".convert::ToInt($_GET['id']).".".$ext);
}

db("DELETE FROM ".dba::get('sponsoren')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(_sponsor_deleted, "?index=admin&amp;admin=sponsors");
