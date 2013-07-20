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
    if(checkme() == "unlogged" || checkme() < 2)
    {
        $index = error(_error_wrong_permissions);
    } else {

        if(isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;
        $entrys = cnt(dba::get('away'));
        $qry = db("SELECT * FROM ".dba::get('away')."
               ORDER BY id DESC
               LIMIT ".($page - 1)*($maxaway=config('m_away')).",".$maxaway."");

        $show = '';
        while($get = _fetch($qry))
        {
            if($get['start'] > time()) $status = _away_status_new;
            if($get['start'] <= time() && $get['end'] >= time()) $status = _away_status_now;
            if($get['end'] < time()) $status = _away_status_done;

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            if(userid() == $get['userid'] || checkme() == "4")
            {
                $value = show("page/button_edit_single", array("id" => $get['id'],
                        "action" => "action=edit",
                        "title" => _button_title_edit));
            } else {
                $value = "&nbsp;";
            }

            if($get['end'] < time()) $value = "&nbsp;";

            checkme() == 4 ? $delete = show("page/button_delete_single", array("id" => $get['id'],
                    "action" => "action=del",
                    "title" => _button_title_del,
                    "del" => _confirm_del_entry)) : $delete = "&nbsp;";

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