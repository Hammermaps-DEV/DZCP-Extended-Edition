<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        $qry = db("SELECT * FROM ".dba::get('votes')."
                   WHERE forum = 0
                   ORDER BY datum DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "index=admin&amp;admin=votes&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "index=admin&amp;admin=votes&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => _confirm_del_vote));
          if($get['menu'] == "1") $icon = "yes";
          else $icon = "no";

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/votes_show", array("date" => date("d.m.Y",$get['datum']),
                                                   "vote" => string::decode($get['titel']),
                                                   "class" => $class,
                                                   "edit" => $edit,
                                                   "icon" => $icon,
                                                   "delete" => $delete,
                                                   "autor" => autor($get['von']),
                                                   "id" => $get['id']));
        }

        $show = show($dir."/votes", array("head" => _votes_head,
                                          "date" => _datum,
                                          "autor" => _autor,
                                          "add" => _votes_admin_head,
                                          "stimmen" => _votes_stimmen,
                                          "titel" => _titel,
                                          "yesno" => _yesno,
                                          "legende" => _legende,
                                          "legendemenu" => _vote_legendemenu,
                                          "show" => $show_));