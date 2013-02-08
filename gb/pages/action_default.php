<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $add = show(_gb_eintragen, array("id" => $_GET['id']));

    if(isset($_GET['page']))  $page = $_GET['page'];
    else $page = 1;
    if(!permission("gb") && $gb_activ == '1') $activ = "WHERE public = 1";
    elseif(permission("gb") && $gb_activ == '1') $activ = "";
    elseif(permission("gb") && $gb_activ == '0') $activ = "";
    elseif($gb_activ == '0') $activ = "";
    $qry = db("SELECT * FROM ".$db['gb']."
               ".$activ."
                   ORDER BY datum DESC
             LIMIT ".($page - 1)*$maxgb.",".$maxgb."");

    $entrys = cnt($db['gb']);
    $i = $entrys-($page - 1)*$maxgb;

    if(_rows($qry))
    {
        while($get = _fetch($qry))
        {
            if($get['hp']) $gbhp = show(_hpicon, array("hp" => $get['hp']));
            else $gbhp = "";

            if($get['email']) $gbemail = show(_emailicon, array("email" => eMailAddr($get['email'])));
            else $gbemail = "";

            if($get['reg'] == $userid || permission("gb"))
            {
                $edit = show("page/button_edit_single", array("id" => $get['id'],
                        "action" => "action=do&amp;what=edit",
                        "title" => _button_title_edit));
                $delete = show("page/button_delete_single", array("id" => $get['id'],
                        "action" => "action=do&amp;what=delete",
                        "title" => _button_title_del,
                        "del" => convSpace(_confirm_del_entry)));

                $comment = show(_gb_commenticon, array("id" => $get['id'],
                        "title" => _button_title_comment));
            } else {
                $delete = "";
                $edit = "";
                $comment = "";
            }
            if(permission("gb") && $gb_activ == 1)
            {
                $public = ($get['public'] == 1)
                ? '<a href="?action=do&amp;what=unset&amp;id='.$get['id'].'"><img src="../inc/images/public.gif" alt="" title="nicht ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>'
                        : '<a href="?action=do&amp;what=set&amp;id='.$get['id'].'"><img src="../inc/images/nonpublic.gif" alt="" title="ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>';	  } else {
                    $public = "";
                }

                if($get['reg'] == "0")
                {
                    $gbtitel = show(_gb_titel_noreg, array("postid" => $i,
                            "nick" => re($get['nick']),
                            "edit" => $edit,
                            "delete" => $delete,
                            "comment" => $comment,
                            "public" => $public,
                            "email" => $gbemail,
                            "datum" => date("d.m.Y", $get['datum']),
                            "uhr" => _uhr,
                            "zeit" => date("H:i", $get['datum']),
                            "hp" => $gbhp));
                } else {
                    $gbtitel = show(_gb_titel, array("postid" => $i,
                            "nick" => autor($get['reg']),
                            "edit" => $edit,
                            "delete" => $delete,
                            "uhr" => _uhr,
                            "comment" => $comment,
                            "public" => $public,
                            "id" => $get['reg'],
                            "email" => $gbemail,
                            "datum" => date("d.m.Y", $get['datum']),
                            "zeit" => date("H:i", $get['datum']),
                            "hp" => $gbhp));
                }

                if($chkMe == "4") $posted_ip = $get['ip'];
                else $posted_ip = _logged;

                $show .= show($dir."/gb_show", array("gbtitel" => $gbtitel,
                        "nachricht" => bbcode($get['nachricht']),
                        "editby" => bbcode($get['editby']),
                        "ip" => $posted_ip));
                $i--;
        }
    } else {
        $show = show(_no_entrys_yet, array("colspan" => "2"));
    }

    $seiten = nav($entrys,$maxgb,"?action=nav");

    if(!ipcheck("gb", $flood_gb))
    {
        if(isset($userid))
        {
            $form = show("page/editor_regged", array("nick" => autor($userid),
                    "von" => _autor));
        } else {
            $form = show("page/editor_notregged", array("nickhead" => _nick,
                    "emailhead" => _email,
                    "hphead" => _hp,
                    "postemail" => ""));
        }

        $entry = show($dir."/add", array("titel" => _eintragen_titel,
                "nickhead" => _nick,
                "add_head" => _gb_add_head,
                "emailhead" => _email,
                "what" => _button_value_add,
                "security" => _register_confirm,
                "ed" => "",
                "reg" => "",
                "whaturl" => "addgb",
                "hphead" => _hp,
                "preview" => _preview,
                "id" => $_GET['id'],
                "form" => $form,
                "posthp" => "",
                "postnick" => "",
                "posteintrag" => "",
                "ip" => _iplog_info,
                "error" => "",
                "eintraghead" => _eintrag));
    } else {
        $entry = "";
    }

    $index = show($dir."/gb",array("gbhead" => _gb_head,
            "show" => $show,
            "add" => $add,
            "entry" => $entry,
            "addgb" => $addgb,
            "seiten" => $seiten));
}
?>