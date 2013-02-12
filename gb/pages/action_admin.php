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
    if(!permission("gb"))
    {
        $index = error(_error_wrong_permissions, 1);
    } else {
        if($_GET['do'] == "addcomment")
        {
            $qry = db("SELECT * FROM ".$db['gb']."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
            $get = _fetch($qry);

            if($get['hp']) $gbhp = show(_hpicon, array("hp" => $get['hp']));
            else $gbhp = "";

            if($get_email) $gbemail = show(_emailicon, array("email" => eMailAddr($get['email'])));
            else $gbemail = "";

            if(permission("gb")) $comment = show(_gb_commenticon, array("id" => $get['id']));
            else $comment = "";

            if($get['reg'] == "0")
            {
                $gbtitel = show(_gb_titel_noreg, array("postid" => "?",
                        "nick" => re($get['nick']),
                        "edit" => "",
                        "delete" => "",
                        "comment" => "",
                        "public" => "",
                        "uhr" => _uhr,
                        "email" => $gbemail,
                        "datum" => date("d.m.Y", $get['datum']),
                        "zeit" => date("H:i", $get['datum']),
                        "hp" => $gbhp));
            } else {
                $gbtitel = show(_gb_titel, array("postid" => "?",
                        "nick" => data($get['reg'], "nick"),
                        "edit" => "",
                        "public" => "",
                        "delete" => "",
                        "uhr" => _uhr,
                        "comment" => "",
                        "id" => $get['reg'],
                        "email" => $gbemail,
                        "datum" => date("d.m.Y", $get['datum']),
                        "zeit" => date("H:i", $get['datum']),
                        "hp" => $gbhp));
            }

            $entry = show($dir."/gb_show", array("gbtitel" => $gbtitel,
                    "nachricht" => bbcode($get['nachricht']),
                    "editby" => bbcode($get['editby']),
                    "ip" => $get['ip']));

            $index = show($dir."/gb_addcomment", array("head" => _gb_addcomment_head,
                    "entry" => $entry,
                    "what" => _button_value_add,
                    "id" => $_GET['id'],
                    "head_gb" => _gb_addcomment_headgb));
        } elseif($_GET['do'] == "postcomment") {
            $qry = db("SELECT * FROM ".$db['gb']."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
            $get = _fetch($qry);

            $comment = show($dir."/commentlayout", array("nick" => autor(convert::ToInt($userid)),
                    "datum" => date("d.m.Y H:i", time())._uhr,
                    "comment" => up($_POST['comment'], 1),
                    "nachricht" => $get['nachricht']));

            $upd = db("UPDATE ".$db['gb']."
                 SET `nachricht` = '".$comment."'
                 WHERE id = '".convert::ToInt($_GET['id'])."'");

            $index = info(_gb_comment_added, "../gb/");
        }
    }
}
?>