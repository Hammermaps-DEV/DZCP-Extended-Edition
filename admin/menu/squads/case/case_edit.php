<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        $qry = db("SELECT * FROM ".dba::get('squads')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $pos = db("SELECT pos,name FROM ".dba::get('squads')." ORDER BY pos");
        while($getpos = _fetch($pos))
        {
          if($getpos['name'] != $get['name'])
          {
            $mpos = db("SELECT pos FROM ".dba::get('squads')."
                        WHERE id != '".convert::ToInt($get['id'])."'
                        AND pos = '".convert::ToInt(($get['pos']-1))."'");
            $mp = _fetch($mpos);

            $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                    "what" => _nach.' '.string::decode($getpos['name']),
                                                    "sel" => ($getpos['pos'] == $mp['pos'] ? 'selected="selected"' : '')));
          }
        }

        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat`
                           ORDER BY s1.name, s2.pos");
        $i = 1;
        $thiskat = '';
        while($getnav = _fetch($qrynav))
        {
          if($thiskat != $getnav['kat']) {
            $navigation .= '
              <option class="dropdownKat" value="lazy">'.string::decode($getnav['katname']).'</option>
              <option value="'.string::decode($getnav['placeholder']).'-1">-> '._admin_first.'</option>
            ';
          }
          $thiskat = $getnav['kat'];
          $sel[$i] = ($getnav['url'] == '?index=squads&amp;action=shows&amp;id='.convert::ToInt($_GET['id'])) ? 'selected="selected"' : '';

          $navigation .= empty($getnav['name']) ? '' : '<option value="'.string::decode($getnav['placeholder']).'-'.($getnav['pos']+1).'" '.$sel[$i].'>'._nach.' -> '.navi_name(string::decode($getnav['name'])).'</option>';

          $i++;
        }

        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true);
        foreach($files as $file)
        {
          if(preg_match("#\.gif|.jpg|.png#Uis",$file))
            $gameicons .= show(_select_field, array("value" => $file,
                                                    "sel" => ($file == $get['icon'] ? 'selected="selected"' : ''),
                                                    "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file))));
        }

        foreach($picformat AS $end)
        {
          if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end))
          {
            $image = '<img src="inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'.'.$end.'" width="200" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=inc/images/squads/'.convert::ToInt($_GET['id']).'.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
            break;
          }
        }

        foreach($picformat AS $end)
        {
          if(file_exists(basePath.'/inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end))
          {
            $logoimage = '<img src="inc/images/uploads/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end.'" height="60" alt="" onmouseover="DZCP.showInfo(\'<tr><td><img src=inc/images/squads/'.convert::ToInt($_GET['id']).'_logo.'.$end.' alt= /></tr></td>\')" onmouseout="DZCP.hideInfo()" /><br />';
            break;
          }
        }

        $show = show($dir."/squads_edit", array("memberadminaddheader" => _member_admin_edit_header,
                                                "squad" => _member_admin_squad,
                                                "id" => convert::ToInt($_GET['id']),
                                                "pos" => _position,
                                                "icon" => _member_admin_icon,
                                                "gameicons" => $gameicons,
                                                "logo" => _team_logo,
                                                "value" => _button_value_edit,
                                                "status" => _status,
                                                "aktiv"  => _sq_aktiv,
                                                "inaktiv" => _sq_inaktiv,
                                                "sstatus" => _sq_sstatus,
                                                "banner" => _sq_banner,
                                                "image" => $image,
                                                "logoimage" => $logoimage,
                                                "desc" => _dl_besch,
                                                "beschreibung" => string::decode($get['beschreibung']),
                                                "cstatus" => ($get['status'] ? 'selected="selected"' : ''),
                                                "first" => _admin_first,
                                                "info" => _admin_squad_show_info,
                                                "navi" => _admin_squads_nav,
                                                "upload" => _member_admin_icon_upload,
                                                "sshown" => ($get['shown'] ? 'selected="selected"' : ''),
                                                "nothing" => _nothing,
                                                "selr" => ($get['navi'] ? 'selected="selected"' : ''),
                                                                                                "selt" => ($get['team_show'] ? 'selected="selected"' : ''),
                                                                                                "navigation" => $navigation,
                                                                                                "roster" => _admin_sqauds_roster,
                                                                                              "navigation" => $navigation,
                                                                                              "nav_info" => _admin_squads_nav_info,
                                                                                                "no_navi" => _admin_squads_no_navi,
                                                                                              "teams" => _admin_squads_teams,
                                                                                              "show" => _show,
                                                "dontshow" => _dont_show,
                                                "ssquad" => string::decode($get['name']),
                                                "sgame" => string::decode($get['game']),
                                                "positions" => $positions,
                                                "check_show" => _button_value_show,
                                                "game" => _member_admin_game));