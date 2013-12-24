<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$show = show($dir."/form_profil", array("head" => _profile_add_head,
                                        "name" => _profile_name,
                                        "type" => _profile_type,
                                        "value" => _button_value_add,
                                        "kat" => _profile_kat,
                                        "form_kat" => _profile_kat_dropdown,
                                        "form_type" => _profile_type_dropdown));