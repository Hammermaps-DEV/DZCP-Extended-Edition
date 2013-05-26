<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class language
{
    private static $language = '';
    private static $languages = '';
    private static $language_files = array();
    private static $user_agent = '';

    /**
     * Erkenne die Sprache des Users am Browser
     */
    private static function detect_language()
    {
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            self::$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
        else if ($_SERVER['HTTP_USER_AGENT'])
        {
            self::$user_agent = explode(";", $_SERVER['HTTP_USER_AGENT']);
            for ($i=0; $i < sizeof(self::$user_agent); $i++)
            {
                self::$languages = explode("-",self::$user_agent[$i]);
                if (sizeof(self::$languages) == 2)
                {
                    if (strlen(trim(self::$languages[0])) == 2)
                    {
                        $size = sizeof(self::$language);
                        self::$language[$size]=trim(self::$languages[0]);
                    }
                }
            }
        }
        else
            self::$language = settings('language');
    }

    private static function check_language($lng='')
    { return(file_exists(basePath.'/inc/lang/languages/'.$lng.'.php')); }

    public static function set_language($language = '')
    {
        if ($language != '')
            $_SESSION['language'] = $language;
        else
        {
            self::detect_language();
            $_SESSION['language'] = (cookie::get('language') ? cookie::get('language') : self::$language);
        }

        if(isset($_SESSION['language']))
        {
            if (self::check_language($_SESSION['language']))
            {
                self::$language = $_SESSION['language'];
                cookie::put('language', self::$language);
            }
            else
            {
                self::$language = settings('language');
                cookie::put('language', self::$language);
            }
        }
        else
        {
            self::$language = settings('language');
            cookie::put('language', self::$language);
        }

        cookie::save(); // Save Cookie
    }

    public static function run_language($language='')
    {
        if(!count(self::$language_files=get_files(basePath.'/inc/lang/languages/',false,true,array('php'))))
            die('No language files found in "inc/lang/languages/*"!');

        self::set_language($language);
        require_once(basePath."/inc/lang/global.php");
        require_once(basePath.'/inc/lang/languages/'.self::$language.'.php');
        header("Content-type: text/html; charset="._charset);
    }

    public static function get_language()
    { return self::$language; }

    public static function get_language_files()
    { return self::$language_files; }

    public static function get_meta()
    {
        $meta='';
        if(count(self::$language_files) >= 1)
        {
            foreach(self::$language_files as $file)
            {
                $file = explode('.',$file);
                $file = substr($file[0], 0, 2);
                $meta .= '    <meta http-equiv="Content-Language" content="'.$file.'"/>'."\n";
            }
        }

        return substr($meta, 0, -1);
    }

    public static function get_menu($lang='')
    {
        $options = '';
        if(count(self::$language_files) >= 1)
        {
            foreach(self::$language_files as $file)
            {
                $file = explode('.',$file);
                $firstString = substr($file[0], 0,1);
                $lang_name = strtoupper($firstString).substr($file[0], 1);
                $options .= '<option value="'.$file[0].'" '.($file[0] == $lang ? 'selected="selected"' : '').'> '.$lang_name.'</option>';
            }
        }

        return '<select id="language" name="language" class="dropdown">'.'<option value="default" '.( $lang == 'default' ? 'selected="selected"' : '').'> '._default.'</option>'.$options.'</select>';
    }
}