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
    $where = $where.' - '._away_list;
    if($chkMe == "unlogged" || $chkMe < 2)
    {
        $index = error(_error_wrong_permissions, 1);
    } else {

        if(isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;
        $entrys = cnt($db['away']);
        $qry = db("SELECT * FROM ".$db['away']."
               ORDER BY id DESC
               LIMIT ".($page - 1)*$maxaway.",".$maxaway."");

        $show = '';
        while($get = _fetch($qry))
        {
            if($get['start'] > time()) $status = _away_status_new;
            if($get['start'] <= time() && $get['end'] >= time()) $status = _away_status_now;
            if($get['end'] < time()) $status = _away_status_done;

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            if($userid == $get['userid'] || $chkMe == "4")
            {
                $value = show("page/button_edit_single", array("id" => $get['id'],
                        "action" => "action=edit",
                        "title" => _button_title_edit));
            } else {
                $value = "&nbsp;";
            }

            if($get['end'] < time()) $value = "&nbsp;";

            $chkMe == 4 ? $delete = show("page/button_delete_single", array("id" => $get['id'],
                    "action" => "action=del",
                    "title" => _button_title_del,
                    "del" => convSpace(_confirm_del_entry))) : $delete = "&nbsp;";

            $info = show($dir."/button_info", array("id" => $get['id'],
                    "action" => "action=info",
                    "title" =>"Info"));

            $show .= show($dir."/away_show", array("class"=>$class,
                    "id"=>$get["id"],
                    "status"=>$status,
                    "von"=>date("d.m.y",$get['start']),
                    "bis"=>date("d.m.y",$get['end']),
                    "grund"=>$get["titel"],
                    "value"=>$value,
                    "del"=>$delete,
                    "nick"=>autor($get['userid']),
                    "details"=>$info));
        }
        if(!$show) $show = _away_no_entry;

        $nav = nav($entrys,$maxaway,"?");
        $index = show($dir."/away", array("head" => _away_head,
                "show" => $show,
                "user" => _user,
                "titel" => _banned_reason,
                "from" => _from,
                "to" => _away_to,
                "status" => _status,
                "submit" => _button_value_addto,
                "nav" => $nav));
    }
}
?>