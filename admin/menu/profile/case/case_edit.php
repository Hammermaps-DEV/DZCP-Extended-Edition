<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        $qry = db("SELECT * FROM ".dba::get('profile')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $shown = str_replace("<option value='".$get['shown']."'>", "<option selected=\"selected\" value='".$get['shown']."'>", _profile_shown_dropdown);
          $kat = str_replace("<option value='".$get['kid']."'>", "<option selected=\"selected\" value='".$get['kid']."'>", _profile_kat_dropdown);
          $type = str_replace("<option value='".$get['type']."'>", "<option selected=\"selected\" value='".$get['type']."'>", _profile_type_dropdown);

        $show = show($dir."/form_profil_edit", array("name" => _profile_name,
                                                                             "p_name" => string::decode($get['name']),
                                                                             "kat" => _profile_kat,
                                                                             "type" => _profile_type,
                                                                             "id" => $_GET['id'],
                                                     "value" => _button_value_edit,
                                                                             "shown" => _profile_shown,
                                                                             "form_shown" => $shown,
                                                                             "form_kat" => $kat,
                                                                             "form_type" => $type,
                                                     "head" => _profile_edit_head));