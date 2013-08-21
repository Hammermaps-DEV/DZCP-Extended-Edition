<?php
if(!defined('IN_DZCP'))
    exit();

if($_COOKIE['agb'] =! true)
    $index = show("/msg/agb_error");
else
{
    $_SESSION['type'] = isset($_POST['type']) ? $_POST['type'] : $_SESSION['type'];

    if(function_exists('ftp_connect') && function_exists('ftp_login') && function_exists('ftp_site'))
    {
        FTP::init(); $jumplink = show("/msg/jumplink"); $ftp_port = 21; $main = ''; $core = ''; $next = false;
        $set_chmod_ftp = false; $disabled = ''; $nextlink = ''; $jumplink = ''; $success_status = '';
        $ftp_host = isset($_POST['host']) ? $_POST['host'] : 'localhost';
        $ftp_pfad = isset($_POST['pfad']) ? $_POST['pfad'] : '/';
        $ftp_user = isset($_POST['ftp_user']) ? $_POST['ftp_user'] : 'root';
        $ftp_pwd = isset($_POST['ftp_pwd']) ? $_POST['ftp_pwd'] : '';
        $_SESSION['ftp_host'] = ''; $_SESSION['ftp_pfad'] = '';
        $_SESSION['ftp_user'] = ''; $_SESSION['ftp_pwd'] = '';

        if(isset($_GET['do']) && $_GET['do'] == 'check')
        {
            $ftp_host_array = explode(':', $ftp_host);
            if(count($ftp_host) >= 2)
            {
                $ftp_port = $ftp_host_array[1];
                $ftp_host_save = $ftp_host_array[0];
            }
            else
                $ftp_host_save = $ftp_host;

            FTP::set('host',$ftp_host_save);
            FTP::set('port',$ftp_port);
            FTP::set('user',$ftp_user);
            FTP::set('pass',$ftp_pwd);

            if(FTP::connect())
            {
                if(FTP::login())
                {
                    $next = true;
                    FTP::move($ftp_pfad);
                    $dirs = FTP::nlist();

                    $check_list = array('/admin','/antispam.php','/artikel','/awards','/away','/banner','/clankasse','/clanwars','/contact','/downloads','/forum','/gallery','/gb','/glossar','/impressum','/inc','/index.php','/kalender','/links','/linkus','/membermap','/news','/online','/rankings','/rss.php','/search','/server','/serverliste','/shout','/sites','/sponsors','/squads','/stats','/teamspeak','/upload','/user','/votes','/_installer');
                    foreach ($check_list as $list)
                    { $list_new[] = str_replace('//', '/', $ftp_pfad.$list); }
                    $check_list = $list_new;
                    unset($list_new);

                    asort($check_list);
                    foreach ($check_list as $list)
                    {
                        $what = "Ordner:&nbsp;";
                        $exp = explode('.', str_replace('/', '', str_replace($ftp_pfad, '', $list)));
                        if(count($exp) >= 2)
                        { if($exp[1] == 'php') $what = "Datei:&nbsp;"; }

                        if(in_array($list, $dirs))
                            $main .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='green'>"._true."<b>".$what."</b></font></td><td><font color='green'>".$list."</font></td></tr></table>";
                        else
                        {
                            $next = false;
                            $main .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='red'>"._false."<b>".$what."</b></font></td><td><font color='red'>".$list."</font><br /></td></tr></table>";
                        }
                    }

                    FTP::move($ftp_pfad.'/inc');
                    $dirs = FTP::nlist();

                    $check_list = array('/inc/additional-addons','/inc/additional-functions','/inc/additional-kernel','/inc/additional-languages','/inc/additional-tpl','/inc/ajax.php','/inc/api.php','/inc/apic.php','/inc/apie.php','/inc/buffer.php','/inc/cache.php','/inc/common.php','/inc/config.php','/inc/cookie.php','/inc/database.php','/inc/debugger.php','/inc/gameq','/inc/gameq.php','/inc/images','/inc/kernel.php','/inc/lang','/inc/menu-functions','/inc/secure.php','/inc/thumbgen.php','/inc/tinymce','/inc/tinymce_files','/inc/_cache','/inc/_logs','/inc/_templates_','/inc/_version.php');
                    foreach ($check_list as $list)
                    { $list_new[] = str_replace('//', '/', $ftp_pfad.$list); }
                    $check_list = $list_new;
                    unset($list_new);

                    asort($check_list);
                    foreach ($check_list as $list)
                    {
                        $what = "Ordner:&nbsp;";
                        $exp = explode('.', str_replace('/', '', str_replace($ftp_pfad, '', $list)));
                        if(count($exp) >= 2)
                        { if($exp[1] == 'php') $what = "Datei:&nbsp;"; }

                        if(in_array($list, $dirs))
                            $core .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='green'>"._true."<b>".$what."</b></font></td><td><font color='green'>".$list."</font></td></tr></table>";
                        else
                        {
                            $next = false;
                            $core .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='red'>"._false."<b>".$what."</b></font></td><td><font color='red'>".$list."</font><br /></td></tr></table>";
                        }
                    }
                }
                else
                    $success_status = writemsg(prepare_no_ftp_login,true);
            }
            else
                $success_status = writemsg(prepare_no_ftp_connect,true);
        }

        if($next)
        {
            $_SESSION['ftp_host'] = $ftp_host;
            $_SESSION['ftp_pfad'] = $ftp_pfad;
            $_SESSION['ftp_user'] = $ftp_user;
            $_SESSION['ftp_pwd'] = $ftp_pwd;
            $disabled = 'disabled="disabled"';
            $success_status = writemsg(ftp_files_success,false);
            $nextlink = show("/msg/nextlink",array("ac" => 'action=prepare'));
        }

        $index = show("ftp",array("disabled" => $disabled, "main" => $main, "core" => $core, "success_status" => $success_status, "next" => $nextlink, "jump" => $jumplink, "ftp_host" => $ftp_host, "ftp_pfad" => $ftp_pfad, "ftp_user" => $ftp_user, "ftp_pwd" => $ftp_pwd));
    }
    else
        $index = show("ftp",array("disabled" => '', "main" => '', "core" => '', "success_status" => '', "next" => '', "jump" => $jumplink, "ftp_host" => '', "ftp_pfad" => '', "ftp_user" => '', "ftp_pwd" => ''));
}