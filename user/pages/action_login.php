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
    ###############
    ## Userlogin ##
    ###############
    $where = _site_user_login;
    switch ($do)
    {
        case 'yes':
            ## Prüfe ob der Secure Code aktiviert ist und richtig eingegeben wurde ##
            if(config('securelogin') && ($_POST['secure'] != $_SESSION['sec_login'] || !isset($_POST['secure']) || empty($_SESSION['sec_login'])))
            {
                ## Der Secure Code ist falsch ##
                $index = error(_error_invalid_regcode, 1);
            }
            else
            {
                ## Username und Passwort prüfen ##
                if(isset($_POST['pwd']) && isset($_POST['user']) && login($_POST['user'],$_POST['pwd'],isset($_POST['permanent']) ? true : false))
                    header("Location: ?action=userlobby"); ## Zur Userlobby weiterleiten ##
                else
                {
                    if(isset($_POST['user']))
                    {
                        ## User Login war fehlerhaft ##
                        $qry = db("SELECT id FROM ".$db['users']." WHERE user = '".$_POST['user']."'");

                        if(_rows($qry))
                        {
                            $get = _fetch($qry);

                            ## Schreibe IP in die IPCheck ##
                            wire_ipcheck("trylogin(".$get['id'].")");
                        }
                    }

                    ## User Abmelden ##
                    logout();

                    ## Error anzeigen ##
                    $index = error(_login_pwd_dont_match);
                }
            }
        break;
        default:
            if($chkMe == "unlogged")
                $index = show($dir."/login", array("secure" => (config('securelogin') ? show($dir.'/secure', array('help' => _login_secure_help, 'security' => _register_confirm)) : '')));
            else
            {
                ## User Abmelden ##
                logout();

                ## Error anzeigen ##
                $index = error(_error_user_already_in, 1);
            }
        break;
    }
}
?>