<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class rss_feed
{
    private static $xml_item = '';
    private static $xml_channel = '';
    private static $xml_image = '';
    private static $xml_rss = '';

    private static $basic_item_array = array();
    private static $basic_image_array = array();
    private static $basic_config_array = array();

    // ################ Public ################

    public static function init()
    {
        global $clanname,$pagetitle,$clanmail;
        self::$basic_config_array['charset'] = _charset;
        self::$basic_config_array['pagetitle'] = convert::ToHTML($clanname);
        self::$basic_config_array['pagelink'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
        self::$basic_config_array['pagedesc'] = convert::ToHTML($pagetitle);
        self::$basic_config_array['pagemailmaster'] = $clanmail;
        self::$basic_config_array['ttl'] = 120;
    }

    public static function set_main_config($key,$var)
    { self::$basic_config_array[$key] = $var; }

    public static function add_image($imgURL,$imgTitle,$imgLink='',$imgWidth=31,$imgHeight=88) //Only one Image
    {
        $basic_image_array['link'] = $imgLink;
        $basic_image_array['title'] = $imgTitle;
        $basic_image_array['url'] = $imgURL;
        $basic_image_array['width'] = $imgWidth;
        $basic_image_array['height'] = $imgHeight;
    }

    public static function add_item($title,$link,$desc,$author='',$comments_url='',$pubdate='',$category='')
    {
        self::$basic_item_array[] = array('title' => $title,'link' => $link,'desc' => $desc,'author' => $author,
        'comments_url' => $comments_url,'pubdate' => $pubdate,'category' => $category);
    }

    public static function gen_rss($lastbuild='')
    {
        self::rss_xml_image();
        self::rss_xml_channel($lastbuild); 
        self::rss_xml_item();
        self::rss_xml_syntax();
    }

    public static function get_rss()
    { return self::$xml_rss; }

    public static function rss_to_file($filename='rss.xml')
    { file_put_contents(basePath.'/'.$filename, self::$xml_rss); }

    // ################ Private ################

    private static function rss_xml_image()
    {
        if(count(self::$basic_image_array))
        {
            foreach(array('title') as $key) // Convert to HTML
            { self::$basic_image_array[$key] = htmlentities(self::$basic_image_array[$key], ENT_QUOTES, self::$basic_config_array['charset']); }
            self::$xml_image .= '<image>'."\r\n";
            self::$xml_image .= '<width>'.convert::ToString((self::$basic_image_array['width'] > 144 ? 144 : self::$basic_image_array['width'])).'</width>'."\r\n"; // Max. 144
            self::$xml_image .= '<height>'.convert::ToString((self::$basic_image_array['height'] > 400 ? 400 : self::$basic_image_array['height'])).'</height>'."\r\n"; // Max. 400
            self::$xml_image .= '<url>'.self::$basic_image_array['url'].'</url>'."\r\n";
            self::$xml_image .= '<title>'.self::$basic_image_array['title'].'</title>'."\r\n";
            self::$xml_image .= '<link>'.self::$basic_image_array['link'].'</link>'."\r\n";
            self::$xml_image .= '</image>';
        }
    }

    private static function rss_xml_channel($lastbuild='')
    {
        foreach(array('pagedesc','pagemailmaster','link') as $key) // Convert to HTML
        { self::$basic_config_array[$key] = htmlentities(self::$basic_config_array[$key], ENT_QUOTES, self::$basic_config_array['charset']); }
        
        self::$xml_channel .= '<title>'.self::$basic_config_array['pagetitle'].'</title>'."\r\n";
        self::$xml_channel .= '<link>'.self::$basic_config_array['pagelink'].'</link>'."\r\n";
        self::$xml_channel .= '<description>'.self::$basic_config_array['pagedesc'].'</description>'."\r\n";
        self::$xml_channel .= '<lastBuildDate>'.(empty($lastbuild) ? date("H:i:s - j.n.Y") : date("H:i:s - j.n.Y",$lastbuild) ).'</lastBuildDate>'."\r\n";
        self::$xml_channel .= '<webMaster>'.self::$basic_config_array['pagemailmaster'].'</webMaster>'."\r\n";
        self::$xml_channel .= '<ttl>'.convert::ToString(self::$basic_config_array['ttl']).'</ttl>'.(!empty(self::$xml_image) ? "\r\n" : '');
        self::$xml_channel .= (!empty(self::$xml_image) ? self::$xml_image : '');
    }

    private static function rss_xml_item()
    {
        if(count(self::$basic_item_array) >= 1)
        {
            foreach(self::$basic_item_array as $item)
            {
                if(empty($item['title']) || empty($item['link']) || empty($item['desc']))
                    continue;

                self::$xml_item .= '<item>'."\r\n";
                self::$xml_item .= '<title>'.convert::ToTXT($item['title']).'</title>'."\r\n";
                self::$xml_item .= '<link>'.convert::ToHTML($item['link']).'</link>'."\r\n";
                self::$xml_item .= '<description>'.convert::ToHTML($item['desc']).'</description>'."\r\n";

                if(array_key_exists('author', $item) && !empty($item['author']))
                    self::$xml_item .= '<author>'.convert::ToTXT($item['author']).'</author>'."\r\n";

                if(array_key_exists('comments_url', $item) && !empty($item['comments_url']))
                    self::$xml_item .= '<comments>'.convert::ToHTML($item['comments_url']).'</comments>'."\r\n";

                if(array_key_exists('pubdate', $item) && !empty($item['pubdate']))
                    self::$xml_item .= '<pubDate>'.convert::ToTXT($item['pubdate']).'</pubDate>'."\r\n";
                    
                if(array_key_exists('category', $item) && !empty($item['category']))
                    self::$xml_item .= '<category>'.convert::ToTXT($item['category']).'</category>'."\r\n";    

                self::$xml_item .= '</item>';
            }
        }
    }

    private static function rss_xml_syntax()
    {
        self::$xml_rss = '<?xml version="1.0" encoding="ISO-8859-1" ?>'."\r\n".
        '<rss version="2.0">'."\r\n".
            '<channel>'."\r\n".
            self::$xml_channel."\r\n".
            (!empty(self::$xml_item) ? self::$xml_item."\r\n" : '').
            '</channel>'."\r\n".
        '</rss>';
    }
}