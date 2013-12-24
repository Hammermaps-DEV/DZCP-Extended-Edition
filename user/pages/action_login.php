<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
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
            switch (isset($_GET['from']) ? $_GET['from'] : 'default')
            {
                case 'menu': $securimage->namespace = 'menu_login'; break;
                default: $securimage->namespace = 'default'; break;
            }

            if(settings('securelogin') && isset($_POST['secure']) && !$securimage->check($_POST['secure']))
                $index = error(captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode);
            else
            {
                ## Username und Passwort prüfen ##
                if(isset($_POST['pwd']) && isset($_POST['user']) && login($_POST['user'],$_POST['pwd'],isset($_POST['permanent']) ? true : false))
                    header("Location: ?index=user&action=userlobby"); ## Zur Userlobby weiterleiten ##
                else
                {
                    if(isset($_POST['user']))
                    {
                        ## User Login war fehlerhaft ##
                        $qry = db("SELECT id FROM ".dba::get('users')." WHERE user = '".$_POST['user']."'");

                        if(_rows($qry))
                        {
                            $get = _fetch($qry);

                            ## Schreibe Adminlog ##
                            wire_ipcheck("trylogin(".$get['id'].")");

                            if(db("SELECT id FROM ".dba::get('users')." WHERE `id` = ".$get['id']." AND `actkey` != ''",true))
                            {
                                $_SESSION['akl_id'] = $get['id'];
                                $index = error(_profil_locked);
                            }

                            if(db("SELECT id FROM ".dba::get('users')." WHERE `id` = ".$get['id']." AND `actkey` = '' AND level = 0",true))
                            {
                                $index = error(_profil_closed);
                                logout(); ## User Abmelden ##
                            }
                        }
                        else
                            logout(); ## User Abmelden ##
                    }
                    else
                        logout(); ## User Abmelden ##

                    ## Error anzeigen ##
                    if(empty($index))
                        $index = error(_login_pwd_dont_match);
                }
            }
        break;
        default:
            if(checkme() == "unlogged")
                $index = show($dir."/login", array("secure" => (settings('securelogin') ? show($dir.'/secure', array('help' => _login_secure_help, 'security' => _register_confirm)) : '')));
            else
            {
                ## Schreibe Adminlog ##
                wire_ipcheck("doublelog(".userid()."_".visitorIp().")");

                ## User Abmelden ##
                logout();

                ## Error anzeigen ##
                $index = error(_error_user_already_in);
            }
        break;
    }
}