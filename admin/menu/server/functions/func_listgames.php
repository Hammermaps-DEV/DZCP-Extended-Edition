<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
* Gibt eine Liste der Live Games aus
*
* @return string/options
*/
function listgames($game = '')
{
    $protocols_array = GameQ::getGames(); $games = '';
    $block = array('teamspeak3','gamespy','gamespy2','gamespy3','source');
    foreach ($protocols_array AS $gameq => $info)
    {
        if(in_array($gameq,$block)) continue;
        $selected = (!empty($game) && $game != false && $game == $gameq ? 'selected="selected" ' : '');
        $games .= '<option '.$selected.'value="'.$gameq.'">'.htmlentities($info['name']).'</option>';
    }

    return $games;
}