<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['name']))
    $show = error(_profil_no_name);
else if($_POST['kat']=="lazy")
    $show = error(_profil_no_kat);
elseif($_POST['type']=="lazy")
    $show = error(_profil_no_type);
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