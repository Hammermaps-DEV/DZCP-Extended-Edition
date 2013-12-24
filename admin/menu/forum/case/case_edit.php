<?php
/**
 * <DZCP-Extended Edition>
 *
 * @package : DZCP-Extended Edition
 * @author : DZCP Developer Team || Hammermaps.de Developer Team
 * @link
 */

if (_adminMenu != 'true') exit();

$positions='';
$qry = db("SELECT * FROM " . dba::get('f_kats') . " WHERE id = '" . convert::ToInt($_GET['id']) . "'");

while ($get = _fetch($qry))
     {
        $pos = db("SELECT * FROM " . dba::get('f_kats') . " ORDER BY kid");

        while ($getpos = _fetch($pos))
            {
            if ($get['name'] != $getpos['name']) {
                $positions .= show(_select_field, array("value" => $getpos['kid'] + 1,
                                                           "what" => _nach . ' ' . string::decode($getpos['name'])));
            }
     }

    if(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".jpg"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".jpg\" alt=\"\" title=\"\"/>";
    elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".png"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".png\" alt=\"\" title=\"\"/>";
    elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".gif"))
        $icon="<img class=\"icon\" src=\"inc/images/uploads/forum/mainkat/".convert::ToInt($get['id']).".gif\" alt=\"\" title=\"\"/>";
    else $icon=false;

    if($icon!=false){
        $delete="<a href=\"?index=admin&amp;admin=forum&amp;do=deleteicon&amp;kat=mainkat&amp;id=".convert::ToInt($get['id'])."\">"._button_title_del."<a/>";
        $icon_edit = show($dir."/forum/forum_icon_preview", array("icon"=>$icon,
                                                              "delete"=>$delete));
    }
    else $icon_edit="";

    $show = show($dir . "/forum/forum_kat_form", array("fkat" => _config_katname,
                                               "head" => _config_forum_kat_head_edit,
                                               "fkid" => _position,
                                               "what"=>"editkat",
                                               "fart" => _kind,
                                               "id" => convert::ToInt($get['id']),
                                               "sel" => ($get['intern'] ? 'selected="selected"' : ''),
                                               "nothing" => _nothing,
                                               "icon_edit"=>$icon_edit,
                                               "icon"=>_config_forum_icon,
                                               "positions" => $positions,
                                               "public" => _config_forum_public,
                                               "intern" => _config_forum_intern,
                                               "value" => _button_value_edit,
                                               "kat" => string::decode($get['name'])));
}