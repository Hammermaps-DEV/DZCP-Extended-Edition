<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        $thiskat = '';
        $qrynav = db("SELECT s2.*, s1.name AS katname, s1.placeholder FROM ".dba::get('navi_kats')." AS s1 LEFT JOIN ".dba::get('navi')." AS s2 ON s1.`placeholder` = s2.`kat` ORDER BY s1.name, s2.pos");
        while($getnav = _fetch($qrynav))
        {
            if($thiskat != $getnav['kat'])
                $navigation .= '<option class="dropdownKat" value="lazy">'.string::decode($getnav['katname']).'</option><option value="'.string::decode($getnav['placeholder']).'-1">-> '._admin_first.'</option>';

            $thiskat = $getnav['kat'];
            $navigation .= empty($getnav['name']) ? '' : '<option value="'.string::decode($getnav['placeholder']).'-'.($getnav['pos']+1).'">'._nach.' -> '.navi_name(string::decode($getnav['name'])).'</option>';
        }

        $qry = db("SELECT * FROM ".dba::get('squads')." ORDER BY pos"); $positions = '';
        while($get = _fetch($qry))
        {
            $positions .= show(_select_field, array("value" => $get['pos']+1, "sel" => "", "what" => _nach.' '.string::decode($get['name'])));
        }

        $files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,array('gif','jpg','png')); $gameicons = '';
        foreach($files as $file)
        {
            $gameicons .= show(_select_field, array("value" => $file, "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)), "sel" => ""));
        }

        $show = show($dir."/squads_add", array("memberadminaddheader" => _member_admin_add_header,
                                               "squad" => _member_admin_squad,
                                               "pos" => _position,
                                               "value" => _button_value_add,
                                               "icon" => _member_admin_icon,
                                               "info" => _admin_squad_show_info,
                                               "status" => _status,
                                               "aktiv"  => _sq_aktiv,
                                               "inaktiv" => _sq_inaktiv,
                                               "logo" => _team_logo,
                                               "banner" => _sq_banner,
                                               "desc" => _dl_besch,
                                               "sstatus" => _sq_sstatus,
                                               "cstatus" => "",
                                               "navi" => _admin_squads_nav,
                                               "first" => _admin_first,
                                               "show" => _show,
                                               "dontshow" => _dont_show,
                                               "upload" => _member_admin_icon_upload,
                                               "gameicons" => $gameicons,
                                               "positions" => $positions,
                                               "check_show" => _button_value_show,
                                               "roster" => _admin_sqauds_roster,
                                                                                             "navigation" => $navigation,
                                                                                             "nav_info" => _admin_squads_nav_info,
                                                                                             "no_navi" => _admin_squads_no_navi,
                                                                                             "teams" => _admin_squads_teams,
                                                                                             "game" => _member_admin_game));