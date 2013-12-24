<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$positions='';
$qry = db("SELECT * FROM ".dba::get('f_skats')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
while($get = _fetch($qry)) //--> Start while subkat sort
{
    $pos = db("SELECT * FROM ".dba::get('f_skats')." WHERE sid = ".$get['sid']."
                       ORDER BY pos");
    while($getpos = _fetch($pos))
    {
        if($get['kattopic'] != $getpos['kattopic'])
        {
            $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                    "what" => _nach.' '.string::decode($getpos['kattopic'])));
        }
    }

    if(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".jpg"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".jpg\" alt=\"\" title=\"\"/>";
    elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".png"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".png\" alt=\"\" title=\"\"/>";
    elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".gif"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/subkat/".convert::ToInt($get['id']).".gif\" alt=\"\" title=\"\"/>";
    else $icon=false;

    if($icon!=false){
        $delete="<a href=\"?index=admin&amp;admin=forum&amp;do=deleteicon&amp;kat=subkat&amp;id=".convert::ToInt($get['id'])."\">"._button_title_del."<a/>";
        $icon_edit = show($dir."/forum/forum_icon_preview", array("icon"=>$icon,
                                                              "delete"=>$delete));
    }
    else $icon_edit="";


    $show = show($dir."/forum/forum_subkat_form", array("head" => _config_forum_edit_skat,
                                       "fkat" => _config_forum_skatname,
                                       "fstopic" => _config_forum_stopic,
                                       "skat" => string::decode($get['kattopic']),
                                       "what" => "editsubkatsave",
                                       "stopic" => string::decode($get['subtopic']),
                                       "id" => $_GET['id'],
                                       "sid" => $get['sid'],
                                       "icon_edit"=>$icon_edit,
                                       "nothing" => _nothing,
                                       "icon"=>_config_forum_icon,
                                       "tposition" => _position,
                                       "position" => $positions,
                                       "value" => _button_value_edit));
} //--> End while subkat sort