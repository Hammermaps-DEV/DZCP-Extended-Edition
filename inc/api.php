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
     *  Ist verwendetes Gerät ein Mobilgerät
     *
     *  @return boolean
     */
    public final static function is_mobile()
    { return self::$MobileDevice; }

    /**
     * Gibt die Mobile_Detect Klasse zurück für weitere Verwendung.
     * Info: https://github.com/serbanghita/Mobile-Detect
     *
     * @return objekt
     */
    public static function get_mobile_class()
    { return self::$MobileClass; }

    /**
     * Setzt einen neuen Verweis einer Tabelle auf einen Tag.
     * set_dba_sqltb('test', 'test_tabelle'); entspricht der Tabelle 'dzcp_test_tabelle'
     *
     * @param string $tag
     * @param string $table
     * @return boolean
     */
    public static function set_dba_sqltb($tag = '', $table = '')
    { return dba::set($tag, $table); }

    /**
     * Setzt einen neuen Verweis einer Tabelle auf einen Tag, *array()
     * set_dba_sqltb_array(array(array('test' => 'test_tabelle'),array('dl123' => 'downloads123')));
     *
     * @param array() $array
     * @return boolean
     */
    public static function set_dba_sqltb_array($array = array())
    { return dba::set_array($array); }

    /**
     * Gibt einen Verweis zurück für die Verwendung in SQL.
     * get_dba_sqltb('test');
     *
     * Sehe Beispiel:
     * $qrydl = db("SELECT * FROM ".API::get_dba_sqltb('test')." WHERE id = '123'");
     *
     * oder alternativ:
     * $qrydl = db("SELECT * FROM ".dba::get('test')." WHERE id = '123'");
     *
     * @param string $tag
     * @return Ambigous <string, multitype:>
     */
    public static function get_dba_sqltb($tag = '')
    { return dba::get($tag); }

    /**
     * Ersetzt eine bestehende Tabellen Definition.
     *
     * @param string $tag
     * @param string $new_table
     * @return mixed
     */
    public static function replace_dba_sqltb($tag = '', $new_table = '')
    { return replace($tag, $new_table); }

    /**
     * Fügt neue BBCodes mit HTML Ersatz ein. *preg_replace*
     * add_bbcode_rep(array('#\[test\](.*?)\[\/test\]#Uis','#\[test2\](.*?)\[\/test2\]#Uis'),
     *                array('<test_html>$1</test_html>',   '<test2_html>$1</test2_html>')));
     *
     * @param array $bbcode
     * @param array $html_replacement
     * @return boolean
     */
    public static function add_bbcode_rep($bbcode = array(), $html_replacement = array())
    { return self::add_additional_bbcode($bbcode,$html_replacement); }

    /**
     * Aktiviert am WYSIWYG-Editor den Advanced Modus oder deaktiviert ihn.
     *
     * @param boolean $bool
     */
    public static function set_advanced_wysiwyg($bool = false)
    { wysiwyg::set($bool ? 'advanced' : 'normal'); }

    /**
     * Codiert Strings und Texte in UTF8.
     * Schreiben von Werten in die Datenbank.
     *
     * @param string $txt
     * @return uft8 string
     */
    public static function up($txt = '')
    { return string::encode($txt); }

    /**
     *
     * Decodiert Strings und Texte von UTF8.
     * Auslesen von Werten aus der Datenbank.
     *
     * @param string $txt
     * @return string
     */
    public static function re($txt = '')
    { return string::decode($txt); }
}
