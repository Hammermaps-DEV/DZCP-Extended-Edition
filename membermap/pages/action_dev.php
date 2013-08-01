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
class geocoder
{
    static private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";
    static private $resp = array();
    static private $address = '';

    static private function searchLocation($address)
    {
        self::$address = $address;
        if(self::ConnectAPI())
        {
            if(!db("SELECT id FROM `".dba::get('geometry')."` WHERE `location` LIKE '".$address."' LIMIT 1;",true))
            {
                switch(count(self::$resp['address_components']))
                {
                    case 3:
                        $country = self::$resp['address_components'][1]['long_name'];
                        $administrative_area_level_1 = '';
                        $administrative_area_level_2 = '';
                        $administrative_area_level_3 = '';
                    break;
                    case 3:
                        $country = self::$resp['address_components'][2]['long_name'];
                        $administrative_area_level_1 = self::$resp['address_components'][1]['long_name'];
                        $administrative_area_level_2 = '';
                        $administrative_area_level_3 = '';
                    break;
                    case 4:
                        $country = self::$resp['address_components'][3]['long_name'];
                        $administrative_area_level_1 = self::$resp['address_components'][2]['long_name'];
                        $administrative_area_level_2 = self::$resp['address_components'][1]['long_name'];
                        $administrative_area_level_3 = '';
                    break;
                    default:
                        $country = self::$resp['address_components'][4]['long_name'];
                        $administrative_area_level_1 = self::$resp['address_components'][3]['long_name'];
                        $administrative_area_level_2 = self::$resp['address_components'][2]['long_name'];
                        $administrative_area_level_3 = self::$resp['address_components'][1]['long_name'];
                    break;
                }


                db("INSERT INTO `".dba::get('geometry')."` SET
                `location` = '".self::$resp['address_components'][0]['long_name']."',
                `lat` = '".self::$resp['geometry']['location']['lat']."',
                `lng` = '".self::$resp['geometry']['location']['lng']."',
                `country` = '".$country."',
                `administrative_area_level_1` = '".$administrative_area_level_1."',
                `administrative_area_level_2` = '".$administrative_area_level_2."',
                `administrative_area_level_3` = '".$administrative_area_level_3."'");
            }

            return true;
        }

        return false;
    }

    static private function ConnectAPI()
    {
        //Connect
        $resp_json = fileExists(self::$url.urlencode(self::$address));
        if(empty($resp_json) || !$resp_json)
            return false;

        if(!($resp = json_decode($resp_json, true)))
            return false;

        if($resp['status']=='OK')
        {
            unset($resp['status']);
            self::$resp = $resp['results'][0];
            return true;
        }

        return false;
    }
}

$test=geocoder::getLocation('Erkrath');

echo '<pre>';
print_r($test['geometry']['location']);
die();
}