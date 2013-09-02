<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('f_kats')." ORDER BY kid");
while($get = _fetch($qry))
{
	/* Sub Kategorien*/
	$qryk = db("SELECT s1.name,s2.id,s2.kattopic,s2.subtopic,s2.pos
                      FROM ".dba::get('f_kats')." AS s1
                      LEFT JOIN ".dba::get('f_skats')." AS s2
                      ON s1.id = s2.sid
                      WHERE s1.id = '".convert::ToInt($get['id'])."'
                      ORDER BY s2.pos");
	unset($subkats);
	while($getk = _fetch($qryk))
	{
		if(!empty($getk['kattopic']))
		{
			$subkat = show(_config_forum_subkats, array("topic" => string::decode($getk['kattopic']),
			                                            "subtopic" => string::decode($getk['subtopic'])));

			$edit = show("page/button_edit_single", array("id" => convert::ToInt($getk['id']),
			                                              "action" => "admin=forum&amp;do=editsubkat",
			                                              "title" => _button_title_edit));

			$delete = show("page/button_delete_single", array("id" => convert::ToInt($getk['id']),
			                                                  "action" => "admin=forum&amp;do=deletesubkat",
			                                                  "title" => _button_title_del,
			                                                  "del" => _confirm_del_entry));


			$subkats .= show($dir."/forum_show_subkats_show", array("subkat" => $subkat,
			                                                        "delete" => $delete,
			                                                        "class" => ($subcolor % 2) ? "contentMainSecond" : "contentMainFirst",
			                                                        "edit" => $edit));
			$subcolor++;
		}


		$subkatshow = show($dir."/forum_show_subkats", array("head" => _config_forum_head,
		                                               "subkathead" => show(_config_forum_subkathead, array("kat" => string::decode($getk['name']))),
		                                               "subkats" => $subkats,
		                                               "add" => show(_config_forum_subkats_add, array("id" => convert::ToInt($get['id']))),
		                                               "subkat" => _config_forum_subkat,
		                                               "delete" => _deleteicon_blank,
		                                               "edit" => _editicon_blank));


	}
	/* End Sub Kategorien*/

	$edit = show("page/button_edit_single", array("id" => convert::ToInt($get['id']),
	                                              "action" => "admin=".$_GET['admin']."&amp;do=edit",
	                                              "title" => _button_title_edit));
	$delete = show("page/button_delete_single", array("id" => convert::ToInt($get['id']),
	                                                  "action" => "admin=".$_GET['admin']."&amp;do=delete",
	                                                  "title" => _button_title_del,
	                                                  "del" => _confirm_del_entry));



	$mainkats .= show($dir."/forum_show_kats", array("class" => ($maincolor % 2) ? "contentMainSecond" : "contentMainFirst",
													 "kat" => string::decode($get['name']),
													 "id" => convert::ToInt($get['id']),
													 "subkats"=>$subkatshow ,
													 "expand"=>(convert::ToInt($_GET['expand'])==convert::ToInt($get['id']))? 'show':'none',
	                                                 "status" => ($get['intern'] == 1)?_config_forum_intern:_config_forum_public,
	                                                 "skats" => cnt(dba::get('f_skats'), " WHERE sid = '".convert::ToInt($get['id'])."'"),
	                                                 "edit" => $edit,
	                                                 "delete" => $delete));

	$maincolor++;
}
$show = show($dir."/forum", array("head" => _config_forum_head,
                                "mainkat" => _config_forum_mainkat,
                                "edit" => _editicon_blank,
                                "skats" => _cnt,
                                "status" => _config_forum_status,
                                "delete" => _deleteicon_blank,
                                "add" => _config_forum_kats_add,
                                "kats" => $mainkats));
