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

if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    header("Content-Type: text/xml; charset=".(!defined('_charset') ? 'iso-8859-1' : _charset));
    function array_search_channel($key, $var, $array=array())
    {
        foreach ($array as $id => $data)
        { if($data[$key] == $var) return $array[$id]; } return false;
    }

    $sID = $_GET['sID']; $Show_sID = $_GET['show'];
    $get = db("SELECT * FROM ".dba::get('ts')." WHERE `id` = ".$sID." LIMIT 1",false,true);

    $ip_port = TS3Renderer::tsdns($get['host_ip_dns']);

    $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
    $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);

    if(!ping_port($host,$get['query_port'],1))
        die('<br /><center>'._no_connect_to_ts.'</center><br />');

    $cache_hash = md5($host.':'.$port);
    if(Cache::check('teamspeak_'.$cache_hash))
    {
        GameQ::addServers(array(array('id' => 'ts3' ,'type' => 'teamspeak3', 'host' => $host.':'.$port, 'query_port' => $get['query_port'])));
        GameQ::setOption('timeout', 6);
        $results = GameQ::requestData();

        if(!empty($results) && $results)
            Cache::set('teamspeak_'.$cache_hash,$results,config('cache_teamspeak'));
    }
    else
        $results = Cache::get('teamspeak_'.$cache_hash);

    //Put to Renderer
    TS3Renderer::set_data($results,$get);
    TS3Renderer::setConfig('IconDownload',convert::IntToBool($get['customicon']));
    TS3Renderer::setConfig('OnlyChannelsWithUsers',convert::IntToBool($get['showchannel']));

    $userstats = ''; $users = 0; $color = 1;
    foreach($results['ts3']['players'] AS $player)
    {
        if(!$player["client_type"])
        {
            $users++;
            $player_status_icon = $player['client_is_channel_commander'] ? "client_cc_idle.png" : "client_idle.png";
            $player_status_icon = $player['client_is_channel_commander'] && $player['client_flag_talking'] ? "client_cc_talk.png" : $player_status_icon;
            $player_status_icon = $player['client_away'] ? "client_away.png" : $player_status_icon;
            $player_status_icon = $player['client_flag_talking'] && !$player['client_is_channel_commander'] ? "client_talk.png" : $player_status_icon;
            $player_status_icon = !$player['client_output_hardware'] ? "client_snd_disabled.png" : $player_status_icon;
            $player_status_icon = $player['client_output_muted'] ? "client_snd_muted.png" : $player_status_icon;
            $player_status_icon = !$player['client_input_hardware'] ? "client_mic_disabled.png" : $player_status_icon;
            $player_status_icon = $player['client_input_muted'] ? "client_mic_muted.png" : $player_status_icon;
            $priority_speaker = $player['client_is_priority_speaker'] ? '<img src="../inc/images/tsviewer/client_priority.png" alt="" class="tsicon" />' : '';
            $player['client_nickname'] = (mb_strlen(html_entity_decode($player['client_nickname'])) > 20 ? cut($player['client_nickname'],20,true) : $player['client_nickname'] );
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $channel = array_search_channel('cid', $player['cid'], $results['ts3']['channels']);
            $userstats .= show($dir."/userstats", array("player" => '<img src="../inc/images/tsviewer/'.$player_status_icon.'" alt="" class="tsicon" />&nbsp;'.TS3Renderer::rep($player['client_nickname']),
                                                        "player_icons" => $priority_speaker.TS3Renderer::user_groups_icons($player),
                                                        "channel" => '<img src="'.TS3Renderer::channel_icon($channel).'" alt="" class="tsicon" /> '.TS3Renderer::channel_name($channel,true,'',1,true),
                                                        "class" => $class,
                                                        "misc1" => TS3Renderer::time_convert(time()-$player['client_lastconnected']),
                                                        "misc2" => TS3Renderer::time_convert($player['client_idle_time'],true)));
        }
    }

    if(!empty($Show_sID) && $Show_sID != 0 && $Show_sID == $get['id'])
    { $display = "show"; $moreicon = "collapse"; } else { $display = "none"; $moreicon = "expand"; }
    $klapp = show(_klapptext_server_link, array("link" => empty($results['ts3']['virtualserver_name']) ? 'Error on this Server!' : $results['ts3']['virtualserver_name'], "id" => $get['id'], "moreicon" => $moreicon));
    die(show($dir."/servers", array("id" => $get['id'], "user" => $users, "display" => $display, "klapp" => $klapp, "uchannels" => TS3Renderer::render(true), "info" => bbcode(TS3Renderer::welcome(),0,0,1), "userstats" => $userstats)));
}