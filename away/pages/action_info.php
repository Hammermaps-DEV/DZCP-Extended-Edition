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
$where = $where.' - '._info;
  if($chkMe == "unlogged" || $chkMe < "2")
  {
    $index = error(_error_wrong_permissions);
  } else {
    $qry = db("SELECT * FROM ".dba::get('away')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
     $get = _fetch($qry);

    if($get['start'] > time()) $status = _away_status_new;
    if($get['start'] <= time() && $get['end'] >= time()) $status = _away_status_now;
    if($get['end'] < time()) $status = _away_status_done;

    if(empty($get['lastedit'])) $edit = "&nbsp;";
    else $edit = bbcode::parse_html($get['lastedit']);

     $index = show($dir."/info", array("head" => _away_info_head,
                                      "i_reason" => _away_reason,
                                      "i_addeton" => _away_addon,
                                      "i_from_to" => _away_formto,
                                      "i_status" => _status,
                                      "i_info" => _info,
                                      "back" => _away_back,
                                      "nick" => autor($get['userid']),
                                      "von" => date("d.m.Y",$get['start']),
                                      "bis" => date("d.m.Y",$get['end']),
                                      "text" => bbcode::parse_html($get['reason']),
                                      "titel" => string::decode($get['titel']),
                                      "edit" => $edit,
                                      "status" => $status,
                                      "addnew" => date("d.m.Y",$get['date'])." "._away_on." ".date("H:i",$get['date'])._uhr));
  }
}