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
        db("UPDATE ".dba::get('profile')." SET `name`  = '".string::encode($name)."',
                                               `kid`   = '".convert::ToInt($_POST['kat'])."',
                                               `type`  = '".convert::ToInt($_POST['type'])."',
                                               `shown` = '".convert::ToInt($_POST['shown'])."'
                                           WHERE id = '".convert::ToInt($_GET['id'])."'");

        $show = info(_profile_edited,"?index=admin&amp;admin=profile");
    }
}

if(empty($show))
{
    $get = db("SELECT * FROM ".dba::get('profile')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $shown = str_replace("<option value='".$get['shown']."'>", "<option selected=\"selected\" value='".$get['shown']."'>", _profile_shown_dropdown);
    $kat = str_replace("<option value='".$get['kid']."'>", "<option selected=\"selected\" value='".$get['kid']."'>", _profile_kat_dropdown);
    $type = str_replace("<option value='".$get['type']."'>", "<option selected=\"selected\" value='".$get['type']."'>", _profile_type_dropdown);

    $show = show($dir."/form_profil_edit", array("error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""), "p_name" => string::decode($get['name']), "id" => $_GET['id'], "form_shown" => $shown, "form_kat" => $kat, "form_type" => $type));
}