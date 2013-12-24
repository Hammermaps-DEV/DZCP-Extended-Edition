<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$files = get_files(basePath.'/inc/images/uploads/newskat/',false,true);
if($files != false)
{
    $img = '';
    foreach($files as $file)
    {
        $img .= show(_select_field, array("value" => $file, "sel" => "", "what" => $file));
    }

    $show = show($dir."/newskatform", array("head" => _config_newskats_add_head, "kat" => "", "value" => _button_value_add, "nothing" => "", "do" => "addnewskat", "img" => $img));
}