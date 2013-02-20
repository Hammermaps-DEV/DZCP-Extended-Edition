<?php
//-> Teamspeak statusscript
function teamspeak()
{
    global $db, $ajaxJob, $language;

    header('Content-Type: text/html; charset=iso-8859-1');
    if(!$ajaxJob)
    {
        return "<div id=\"navTeamspeakServer\">
        <div style=\"width:100%;padding:10px 0;text-align:center\"><img src=\"../inc/images/ajax_loading.gif\" alt=\"\" /></div>
        <script language=\"javascript\" type=\"text/javascript\">DZCP.initDynLoader('navTeamspeakServer','teamspeak','');</script></div>";
    }
    else
    {
        $settings = settings(array('ts_ip','ts_sport','ts_port','ts_version'));
        if(!empty($settings['ts_ip']) && !empty($settings['ts_sport']) && !empty($settings['ts_port']))
        {
            if(!fsockopen_support())
                return error2(_fopen);

            if(Cache::check('nav_teamspeak_'.$language))
            {
                if(!ping_port($settings['ts_ip'],$settings['ts_sport'],0.3))
                    return '<br /><center>'._no_connect_to_ts.'</center><br />';

                $teamspeak = teamspeakViewer($settings);
                Cache::set('nav_teamspeak_'.$language, $teamspeak, config('cache_teamspeak'));
                return $teamspeak;
            }
            else
                return Cache::get('nav_teamspeak_'.$language);
        }
        else
            return '<br /><center>'._no_ts.'</center><br />';
    }
}
?>