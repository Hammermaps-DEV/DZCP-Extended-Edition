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
    if(!ipcheck("shout", ($flood_shout=config('f_shout'))))
    {
        if(($_POST['protect'] != 'nospam' || empty($_SESSION['sec_shout']) || $_POST['spam'] != $_SESSION['sec_shout'] || empty($_POST['spam'])) && !userid())
            $index = error(_error_invalid_regcode);
        else if(!userid() && (empty($_POST['name']) || trim($_POST['name']) == '') || $_POST['name'] == "Nick")
            $index = error(_empty_nick);
        else if(!userid() && empty($_POST['email']) || $_POST['email'] == "E-Mail")
            $index = error(_empty_email);
        else if(!userid() && !check_email($_POST['email']))
            $index = error(_error_invalid_email);
        else if(check_email_trash_mail($_POST['email']))
            $index = error(_error_trash_mail);
        else if(empty($_POST['eintrag']))
            $index = error(_error_empty_shout);
        else if(settings('reg_shout') == 1 && checkme() == 'unlogged')
            $index = error(_error_unregistered);
        else
        {
            $reg = (!userid() ? $_POST['email'] : userid());
            db("INSERT INTO ".dba::get('shout')." SET
                `datum`  = '".time()."',
                `nick`   = '".string::encode($_POST['name'],'')."',
                `email`  = '".string::encode($reg,'')."',
                `text`   = '".string::encode(substr(str_replace("\n", ' ', $_POST['eintrag']),0,config('shout_max_zeichen')),'')."',
                `ip`     = '".visitorIp()."'");

            wire_ipcheck('shout');

            if(!isset($_GET['ajax']))
                header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
        }
    }
    else
        $index = error(show(_error_flood_post, array("sek" => $flood_shout)));

    if(isset($_GET['ajax']))
    {
        echo str_replace("\n", '', html_entity_decode(strip_tags($index)));
        exit();
    }
}