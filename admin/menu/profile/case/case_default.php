<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

function show_profil_links($kid)
{
    global $dir;
    $qry = db("SELECT * FROM ".dba::get('profile')." WHERE kid = '".$kid."' ORDER BY name"); $color = 0; $show = '';
    while($get = _fetch($qry))
    {
        $shown = ($get['shown'] == 1)
        ? '<a href="?index=admin&amp;admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
                : '<a href="?index=admin&amp;admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="inc/images/no.gif" alt="" title="'._public.'" /></a>';

        if($get['type'] == "1")
            $type = _profile_type_1;
        else if($get['type'] == "2")
            $type = _profile_type_2;
        else if($get['type'] == "3")
            $type = _profile_type_3;

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "index=admin&amp;admin=profile&amp;do=edit","title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "index=admin&amp;admin=profile&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_profil));
        $show .= show($dir."/profil_show", array("class" => $class,"name" => string::decode($get['name']), "type" => $type, "shown" => $shown, "edit" => $edit, "del" => $delete));
    }

    return $show;
}

$show = show($dir."/profil", array("show_about" => show_profil_links('1'),
                                   "show_clan" => show_profil_links('2'),
                                   "show_contact" => show_profil_links('3'),
                                   "show_favos" => show_profil_links('4'),
                                   "show_hardware" => show_profil_links('5'),
                                   "about" => _profile_about,
                                   "clan" => _profile_clan,
                                   "contact" => _profile_contact,
                                   "favos" => _profile_favos,
                                   "hardware" => _profile_hardware,
                                   "name" => _profile_name,
                                   "info" => _navi_info,
                                   "head" => _profile_head,
                                   "add" => _profile_add,
                                   "type" => _profile_type,
                                   "edit" => _editicon_blank,
                                   "del" => _deleteicon_blank,
                                   "shown" => _profile_shown));