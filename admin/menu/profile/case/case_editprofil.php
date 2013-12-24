<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();


        if(empty($_POST['name']))
        {
          $show = error(_profil_no_name);
        } else {
          $name = preg_replace("#[[:punct:]]|[[:space:]]#Uis", "", $_POST['name']);

              $add = db("UPDATE ".dba::get('profile')."
                     SET `name`  = '".string::encode($name)."',
                                   `kid`   = '".convert::ToInt($_POST['kat'])."',
                                   `type`  = '".convert::ToInt($_POST['type'])."',
                                   `shown` = '".convert::ToInt($_POST['shown'])."'
                             WHERE id = '".convert::ToInt($_GET['id'])."'");

              $show = info(_profile_edited,"?index=admin&amp;admin=profile");
        }