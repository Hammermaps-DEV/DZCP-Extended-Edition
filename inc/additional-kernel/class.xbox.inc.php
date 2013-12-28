<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class xbox_live extends client_api_communicate
{
    static private $api_host = 'http://xboxleaders.com';
    static private $api_data = array();
    static private $send_data_api = array();

    public static function set_host($api_host='')
    { self::$api_host = empty($api_host) ? 'http://xboxleaders.com' : $api_host; }

    public static function get_profile($username='')
    {
        self::$send_data_api['gamertag'] = $username;
        self::$send_data_api['apitype'] = 'profile';

        if(!self::get_data()) return false;
        $profile = self::$api_data['profile'];
        if(array_key_exists('status', $profile) && $profile['status'] == 'error')
        {
            $data = $profile['data'];
            DebugConsole::insert_error('xbox_live::get_profile()', 'API Error');
            DebugConsole::insert_error('xbox_live::get_profile()', 'Status: '.$profile['status']);
            DebugConsole::insert_error('xbox_live::get_profile()', 'Code: '.$data['code']);
            DebugConsole::insert_error('xbox_live::get_profile()', 'Msg: '.$data['message']);
            return false;
        }

        return $profile['status'] == 'success' ? $profile : false;
    }

    public static function get_games($username='')
    {
        self::$send_data_api['gamertag'] = $username;
        self::$send_data_api['apitype'] = 'games';

        if(!self::get_data()) return false;
        $games = self::$api_data['games'];
        if(array_key_exists('status', $games) && $games['status'] == 'error')
        {
            $data = $games['data'];
            DebugConsole::insert_error('xbox_live::get_games()', 'API Error');
            DebugConsole::insert_error('xbox_live::get_games()', 'Status: '.$games['status']);
            DebugConsole::insert_error('xbox_live::get_games()', 'Code: '.$data['code']);
            DebugConsole::insert_error('xbox_live::get_games()', 'Msg: '.$data['message']);
            return false;
        }

        return $games['status'] == 'success' ? $games : false;
    }

    public static function get_acheivements($username='',$gameid='')
    {
        self::$send_data_api['gamertag'] = $username;
        self::$send_data_api['gameid'] = $gameid;
        self::$send_data_api['apitype'] = 'achievements';

        if(!self::get_data()) return false;
        $achievements = self::$api_data['achievements'];
        if(array_key_exists('status', $achievements) && $achievements['status'] == 'error')
        {
            $data = $achievements['data'];
            DebugConsole::insert_error('xbox_live::get_acheivements()', 'API Error');
            DebugConsole::insert_error('xbox_live::get_acheivements()', 'Status: '.$achievements['status']);
            DebugConsole::insert_error('xbox_live::get_acheivements()', 'Code: '.$data['code']);
            DebugConsole::insert_error('xbox_live::get_acheivements()', 'Msg: '.$data['message']);
            return false;
        }

        return $achievements['status'] == 'success' ? $achievements : false;
    }

    public static function get_friends($username='')
    {
        self::$send_data_api['gamertag'] = $username;
        self::$send_data_api['apitype'] = 'friends';

        if(!self::get_data()) return false;
        $friends = self::$api_data['friends'];
        if(array_key_exists('status', $friends) && $friends['status'] == 'error')
        {
            $data = $friends['data'];
            DebugConsole::insert_error('xbox_live::get_friends()', 'API Error');
            DebugConsole::insert_error('xbox_live::get_friends()', 'Status: '.$friends['status']);
            DebugConsole::insert_error('xbox_live::get_friends()', 'Code: '.$data['code']);
            DebugConsole::insert_error('xbox_live::get_friends()', 'Msg: '.$data['message']);
            return false;
        }

        return $friends['status'] == 'success' ? $friends : false;
    }

    public static function marketplace_search($search='borderlands')
    {
        self::$send_data_api['query'] = $search;
        self::$send_data_api['apitype'] = 'search';

        if(!self::get_data()) return false;
        $search = self::$api_data['search'];
        if(array_key_exists('status', $search) && $search['status'] == 'error')
        {
            $data = $search['data'];
            DebugConsole::insert_error('xbox_live::marketplace_search()', 'API Error');
            DebugConsole::insert_error('xbox_live::marketplace_search()', 'Status: '.$search['status']);
            DebugConsole::insert_error('xbox_live::marketplace_search()', 'Code: '.$data['code']);
            DebugConsole::insert_error('xbox_live::marketplace_search()', 'Msg: '.$data['message']);
            return false;
        }

        return $search['status'] == 'success' ? $search : false;
    }

    /* ############ Private ############ */

    private static function get_data()
    {
        if(!array_key_exists('gamertag', self::$send_data_api) || self::$send_data_api['apitype'] == 'search')
        {
            DebugConsole::insert_error('xbox_live::get_datas()', 'gamertag is not set');
            return false;
        }

        switch(self::$send_data_api['apitype'])
        {
            case 'achievements':
                $apitype = 'achievements';
                if(!array_key_exists('gameid', self::$send_data_api))
                {
                    DebugConsole::insert_error('xbox_live::get_datas()', 'gameid is not set');
                    return false;
                }
                break;
            case 'search':
                $apitype = 'search';
                if(!array_key_exists('query', self::$send_data_api))
                {
                    DebugConsole::insert_error('xbox_live::get_datas()', 'query is not set');
                    return false;
                }
                break;
            case 'friends': $apitype = 'friends'; break;
            case 'games': $apitype = 'games'; break;
            default:
            case 'profile': $apitype = 'profile'; break;
        }

        unset(self::$send_data_api['apitype']);
        if(!($json_stream = self::send_custom(self::$api_host.'/api/'.$apitype.'.json?'.http_build_query(self::$send_data_api))))
        {
            DebugConsole::insert_error('xbox_live::get_datas()', 'No connection to the API interface');
            return false;
        }

        self::$api_data[$apitype] = json_decode($json_stream,true);
        return true;
    }
}