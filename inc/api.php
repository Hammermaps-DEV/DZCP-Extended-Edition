<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class API extends API_CORE
{
    /**
     *  Ist ein bestimmtes Addon installiert
     *
     *  @return boolean
     */
    public final static function is_addon_exists($addon)
    {
        if(!isset(self::$addon_index))
            return false;

        if(!array_key_exists($addon, self::$addon_index))
            return false;

        return true;
    }

    /**
     *  Ist die richtige Version des Addons installiert
     *
     *  @return boolean
     */
    public final static function is_addon_version($addon,$version)
    {
        if(!isset(self::$addon_index))
            return false;

        if(!array_key_exists($addon, self::$addon_index))
            return false;

        if(self::$addon_index[$addon]['xml']['xml_addon_version'] >= $version)
            return true;

        return false;
    }

    /**
     *  Gibt Informationen über das Addon zurück
     *
     *  @return String
     */
    public final static function get_addon_info($addon)
    { return self::$addon_index[$addon]; }

    /**
     *  Gibt beliebige Informationen aus der addon.xml zurück
     *
     *  @return String
     */
    public final static function get_addon_xml_custom_info($addon,$info)
    {
        $xml = self::$addon_index[$addon]['xml']['xml_addon_obj'];
        return $xml->$info;
    }

    /**
     *  Ist verwendetes Geräte ein Mobilgerät
     *
     *  @return boolean
     */
    public final static function is_mobile()
    { return self::$MobileDevice; }
}
?>