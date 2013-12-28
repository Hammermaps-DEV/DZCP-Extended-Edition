<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$error = '';
if(isset($_POST['name']))
{
    if(empty($_POST['name']))
        $error = _profil_no_name;
    else if($_POST['kat']=="lazy")
        $error = _profil_no_kat;
    elseif($_POST['type']=="lazy")
        $error = _profil_no_type;
    else
    {
        $name = preg_replace("#[[:punct:]]|[[:space:]]#Uis", "", $_POST['name']);
        db("INSERT INTO ".dba::get('profile')." SET `name` = '".string::encode($name)."',
                                                    `type` = '".convert::ToInt($_POST['type'])."',
                                                    `kid`  = '".convert::ToInt($_POST['kat'])."'");
        $insID = database::get_insert_id();
        $feldname = "custom_".$insID;
        db("UPDATE ".dba::get('profile')." SET `feldname` = '".$feldname."' WHERE id = '".convert::ToInt($insID)."'");
        db("ALTER TABLE `".dba::get('users')."` ADD `".$feldname."` VARCHAR( 249 ) NOT NULL");
        $show = info(_profile_added,"?index=admin&amp;admin=profile");
    }
}

if(empty($show))
{
    $kat = isset($_POST['kat']) ? str_replace('value="'.$_POST['kat'].'"', ' selected="selected" value="'.$_POST['kat'].'"', _profile_kat_dropdown) : _profile_kat_dropdown;
    $type = isset($_POST['type']) ? str_replace('value="'.$_POST['type'].'"', ' selected="selected" value="'.$_POST['type'].'"', _profile_type_dropdown) : _profile_type_dropdown;
    $show = show($dir."/form_profil", array( "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                             "name" => isset($_POST['name']) ? $_POST['name'] : '',
                                             "form_kat" => $kat,
                                             "form_type" => $type));
}