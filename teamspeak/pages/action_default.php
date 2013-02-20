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
  if(fsockopen_support())
  {
    if(Cache::check('teamspeak_'.$language) || isset($_GET['cID']))
    {
        if(!ping_port($settings['ts_ip'],$settings['ts_sport'],1))
            $index = '<br /><center>'._no_connect_to_ts.'</center><br />';
        else
        {

      $tsstatus = new TSStatus($settings['ts_ip'], $settings['ts_port'], $settings['ts_sport'], settings('ts_customicon'), settings('ts_showchannel'));
      $tstree = $tsstatus->render(true);

      $users = 0;
      foreach($tsstatus->_userDatas AS $user)
      {
              if($user["client_type"] == 0)
              {
          $users++;
                                                                $icon = "16x16_player_off.png";
                  if($user["client_away"] == 1)                 $icon = "16x16_away.png";
                  else if($user["client_flag_talking"] == 1)    $icon = "16x16_player_on.png";
                  else if($user["client_output_hardware"] == 0) $icon = "16x16_hardware_output_muted.png";
                  else if($user["client_output_muted"] == 1)    $icon = "16x16_output_muted.png";
                  else if($user["client_input_hardware"] == 0)  $icon = "16x16_hardware_input_muted.png";
                  else if($user["client_input_muted"] == 1)     $icon = "16x16_input_muted.png";

                  $flags = array();
                  if(isset($tsstatus->_channelGroupFlags[$user['client_channel_group_id']])) $flags[] = $tsstatus->_channelGroupFlags[$user['client_channel_group_id']];
                  $serverGroups = explode(",", $user['client_servergroups']);
                  foreach ($serverGroups as $serverGroup) if(isset($tsstatus->_serverGroupFlags[$serverGroup])) $flags[] = $tsstatus->_serverGroupFlags[$serverGroup];

          $p = '<img src="../inc/images/tsicons/'.$icon.'" alt="" class="tsicon" />'.rep2($user['client_nickname']).'&nbsp;'.$tsstatus->renderFlags($flags);

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $userstats .= show($dir."/userstats", array("player" => $p,
                                                      "channel" => rep2($tsstatus->getChannelInfos($user['cid'])),
                                                      "misc1" => '',
                                                      "class" => $class,
                                                      "misc2" => '',
                                                      "misc3" => time_convert(time()-$user['client_lastconnected']),
                                                      "misc4" => time_convert($user['client_idle_time'],true)));
          }
      }

      $index = show($dir."/teamspeak", array("name" => $tsstatus->_serverDatas['virtualserver_name'],
                                             "os" => $tsstatus->_serverDatas['virtualserver_platform'],
                                             "uptime" => time_convert($tsstatus->_serverDatas['virtualserver_uptime']),
                                             "user" => $users,
                                             "t_name" => _ts_name,
                                             "t_os" => _ts_os,
                                             "uchannels" => $tstree,
                                             "info" => bbcode($tsstatus->welcome($settings, convert::ToInt($_GET['cID']),$_GET['cName']),0,0,1),
                                             "t_uptime" => _ts_uptime,
                                             "t_channels" => _ts_channels,
                                             "t_user" => _ts_user,
                                             "head" => _ts_head,
                                             "users_head" => _ts_users_head,
                                             "player" => _ts_player,
                                             "channel" => _ts_channel,
                                             "channel_head" => _ts_channel_head,
                                             "max" => $max,
                                             "channels" => $tsstatus->_serverDatas['virtualserver_channelsonline'],
                                             "logintime" => _ts_logintime,
                                             "idletime" => _ts_idletime,
                                             "channelstats" => $channelstats,
                                             "userstats" => $userstats));

    Cache::set('teamspeak_'.$language, $index, config('cache_teamspeak'));
        }
    } else {
      $index = Cache::get('teamspeak_'.$language);
    }
  } else {
    $index = error(_fopen,1);
  }
}
?>