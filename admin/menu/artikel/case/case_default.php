<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$entrys = cnt(dba::get('artikel')); $show = ''; $color = 1;
$qry = db("SELECT * FROM ".dba::get('artikel')." ORDER BY `public` ASC, `datum` DESC LIMIT ".($page - 1)*($maxadminartikel=settings('m_adminartikel')).",".$maxadminartikel."");
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "admin=artikel&amp;do=edit","title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "admin=artikel&amp;do=delete","title" => _button_title_del,"del" => _confirm_del_artikel));
    $titel = show(_artikel_show_link, array("titel" => string::decode(cut($get['titel'],settings('l_newsadmin'))),"id" => $get['id']));
    $public = '<a href="?admin=artikel&amp;do=public&amp;id='.$get['id'].'"><img src="../inc/images/'.($get['public'] ? 'public.gif' : 'nonpublic.gif').'" alt="" title="'._public.'" /></a>';
    $datum = (empty($get['datum']) ? _no_public : date("d.m.y H:i", $get['datum'])._uhr);
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/admin_show_artikel", array("date" => $datum,
                                                    "titel" => $titel,
                                                    "class" => $class,
                                                    "autor" => autor($get['autor']),
                                                    "public" => $public,
                                                    "edit" => $edit,
                                                    "delete" => $delete));
}

$nav = nav($entrys,settings('m_adminnews'),"?admin=artikel");
$show = show($dir."/admin_artikel", array("nav" => $nav,"show" => $show));