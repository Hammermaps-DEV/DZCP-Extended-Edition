<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class bbcode
{
    private static $string = '';
    private static $gl_words = array();
    private static $gl_desc = array();
    private static $use_glossar = true;
    private static $simple_search = array(
      '/\[b\](.*?)\[\/b\]/is',
      '/\[i\](.*?)\[\/i\]/is',
      '/\[u\](.*?)\[\/u\]/is',
      '/\[s\](.*?)\[\/s\]/is',
      '/\[size\=(.*?)\](.*?)\[\/size\]/is',
      '/\[color\=(.*?)\](.*?)\[\/color\]/is',
      '/\[center\](.*?)\[\/center\]/is',
      '/\[font\=(.*?)\](.*?)\[\/font\]/is',
      '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',

      '/\[left\](.*?)\[\/left\]/is',
      '/\[right\](.*?)\[\/right\]/is',

      '/\[url\](.*?)\[\/url\]/is',
      '/\[url\=(.*?)\](.*?)\[\/url\]/is',
      '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',
      '/\[mail\](.*?)\[\/mail\]/is',
      '/\[img\](.*?)\[\/img\]/is',
      '/\[img\=(\d*?)x(\d*?)\](.*?)\[\/img\]/is',
      '/\[img (.*?)\](.*?)\[\/img\]/ise',

      '/\[quote\](.*?)\[\/quote\]/is',
      '/\[quote\=(.*?)\](.*?)\[\/quote\]/is',

      '/\[sub\](.*?)\[\/sub\]/is',
      '/\[sup\](.*?)\[\/sup\]/is',
      '/\[p\](.*?)\[\/p\]/is',

      '/\[bull \/\]/i',
      '/\[copyright \/\]/i',
      '/\[registered \/\]/i',
      '/\[tm \/\]/i');

    private static $simple_replace = array(
      '<strong>$1</strong>',
      '<em>$1</em>',
      '<u>$1</u>',
      '<del>$1</del>',
      '<span style="font-size: $1;">$2</span>',
      '<span style="color: $1;">$2</span>',
      '<div style="text-align: center;">$1</div>',
      '<span style="font-family: $1;">$2</span>',
      '<div style="text-align: $1;">$2</div>',

      '<div style="text-align: left;">$2</div>',
      '<div style="text-align: right;">$2</div>',

      '<a href="$1">$1</a>',
      '<a href="$1">$2</a>',
      '<a href="mailto:$1">$2</a>',
      '<a href="mailto:$1">$1</a>',
      '<img src="$1" alt="" />',
      '<img height="$2" width="$1" alt="" src="$3" />',
      '"<img " . str_replace("&#039;", "\"",str_replace("&quot;", "\"", "$1")) . " src=\"$2\" />"',

      '<blockquote>$1</blockquote>',
      '<blockquote><strong>$1 wrote:</strong> $2</blockquote>',

      '<sub>$1</sub>',
      '<sup>$1</sup>',
      '<p>$1</p>',

      '&bull;',
      '&copy;',
      '&reg;',
      '&trade;');

    private static $lineBreaks_search = array(
      '/\[list(.*?)\](.+?)\[\/list\]/sie',
      '/\[\/list\]\s*\<br \/\>/i',
      '/\[code\](.+?)\[\/code\]/sie',
      '/\[\/code\]\s*\<br \/\>/i',
      '/\[\/quote\]\s*\<br \/\>/i',
      '/\[\/p\]\s*\<br \/\>/i',
      '/\[\/center\]\s*\<br \/\>/i',
      '/\[\/align\]\s*\<br \/\>/i');

    private static $lineBreaks_replace = array(
      "'[list$1]'.str_replace('<br />', '', '$2').'[/list]'",
      "[/list]",
      "'[code]'.str_replace('<br />', '', '$1').'[/code]'",
      "[/code]",
      "[/quote]",
      "[/p]",
      "[/center]",
      "[/align]");

    private static $vid_search = array(
      "/\[googlevideo\](.*?)\[\/googlevideo\]/",
      "/\[myvideo\](.*?)\[\/myvideo\]/",
      "/\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]/",
      "/\[divx\](.*?)\[\/divx\]/",
      "/\[vimeo\]([0-9]{0,})\[\/vimeo\]/",
      "/\[xfire\](.*?)\[\/xfire\]/",
      "/\[golem\](.*?)\[\/golem\]/");

    private static $vid_replace = array(
      "<embed id=VideoPlayback src=http://video.google.de/googleplayer.swf?docid=-$1&hl=de&fs=true style=width:425px;height:344px allowFullScreen=true allowScriptAccess=always type=application/x-shockwave-flash> </embed>",

      "<object wmode=\"opaque\" style=\"width: 425px; height: 344px;\" type=\"application/x-shockwave-flash\" data=\"http://www.myvideo.de/movie/$1\"> </param>
      <param name=\"wmode\" value=\"opaque\">
      <param name=\"movie\" value=\"http://www.myvideo.de/movie/$1\"><param name=\"AllowFullscreen\" value=\"true\"></object>",

      "<object width=\"425\" height=\"344\" wmode=\"opaque\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=de_DE&fs=1&color1=0x3a3a3a&color2=0x999999&border=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param>
      <param name=\"wmode\" value=\"opaque\"><embed src=\"http://www.youtube.com/v/$1&hl=de_DE&fs=1&color1=0x3a3a3a&color2=0x999999&border=0\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>",

      "<object classid=\"clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616\" width=\"425\" height=\"344\" wmode=\"opaque\" codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\">
      <param name=\"custommode\" value=\"none\" /><param name=\"autoPlay\" value=\"false\" /><param name=\"src\" value=\"$1\" />
      <embed type=\"video/divx\" src=\"$1\" custommode=\"none\" width=\"425\" height=\"344\" autoPlay=\"false\" pluginspage=\"http://go.divx.com/plugin/download/\"></embed></object>",

      "<object width=\"425\" height=\"344\" wmode=\"opaque\"><param name=\"allowfullscreen\" value=\"true\" /></param>
      <param name=\"wmode\" value=\"opaque\">
      <param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://www.vimeo.com/moogaloop.swf?clip_id=\\1&server=www.vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=&fullscreen=1\" /><embed src=\"http://www.vimeo.com/moogaloop.swf?clip_id=\\1&server=www.vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=&fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"425\" height=\"344\"></embed></object>",

      "<object width=\"425\" height=\"344\" wmode=\"opaque\"></param>
      <param name=\"wmode\" value=\"opaque\">
      <embed src=\"http://media.xfire.com/swf/embedplayer.swf\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"425\" height=\"344\" flashvars=\"videoid=\\1\"></embed></object>",

      "<object width=\"480\" height=\"270\" wmode=\"opaque\"></param>
      <param name=\"wmode\" value=\"opaque\">
      <param name=\"movie\" value=\"http://video.golem.de/player/videoplayer.swf?id=$1&autoPl=false\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"AllowScriptAccess\" value=\"always\"><embed src=\"http://video.golem.de/player/videoplayer.swf?id=$1&autoPl=false\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" AllowScriptAccess=\"always\" width=\"480\" height=\"270\"></embed></object>");

    public static function init()
    {
        global $ajaxThumbgen;

        if(glossar_enabled && !$ajaxThumbgen)
        {
            $qryglossar = db("SELECT word,glossar FROM ".dba::get('glossar'));
            while($getglossar = _fetch($qryglossar))
            {
                self::$gl_words[] = string::decode($getglossar['word']);
                self::$gl_desc[]  = string::decode($getglossar['glossar']);
            }
        }
    }

    private static function process_list_items($list_items)
    {
        $result_list_items = array();

        // Check for [li][/li] tags
        preg_match_all("/\[li\](.*?)\[\/li\]/is", $list_items, $li_array);
        $li_array = $li_array[1];

        if (empty($li_array))
        {
            // we didn't find any [li] tags
            $list_items_array = explode("[*]", $list_items);
            foreach ($list_items_array as $li_text)
            {
                $li_text = trim($li_text);
                if (empty($li_text))
                {
                    continue;
                }

                $li_text = nl2br($li_text);
                $result_list_items[] = '<li>'.$li_text.'</li>';
            }
        }
        else
        {
            // we found [li] tags!
            foreach ($li_array as $li_text)
            {
                $li_text = nl2br($li_text);
                $result_list_items[] = '<li>'.$li_text.'</li>';
            }
        }

        return implode("\n", $result_list_items);
    }

    //Badword Filter
    private static function badword_filter()
    {
        $words = trim(string::decode(settings('badwords')));
        if(empty($words)) return;
        $words = explode(",",$words);
        if(count($words) >= 1)
        {
            foreach($words as $word)
            { self::$string = preg_replace("#".$word."#i", str_repeat("*", strlen($word)), self::$string); }
        }
    }

    private static function make_url_clickable($matches)
    {
        $ret = '';
        $url = $matches[2];

        if ( empty($url) )
            return $matches[0];
        // removed trailing [.,;:] from URL
        if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true )
        {
            $ret = substr($url, -1);
            $url = substr($url, 0, strlen($url)-1);
        }

        return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
    }

    private static function make_web_ftp_clickable($matches)
    {
        $ret = '';
        $dest = $matches[2];
        $dest = 'http://' . $dest;

        if ( empty($dest) )
            return $matches[0];
        // removed trailing [,;:] from URL
        if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true )
        {
            $ret = substr($dest, -1);
            $dest = substr($dest, 0, strlen($dest)-1);
        }

        return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
    }

    private static function make_email_clickable($matches)
    {
        $email = $matches[2] . '@' . $matches[3];
        return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
    }

    private static function make_clickable()
    {
        self::$string = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', 'self::make_url_clickable', self::$string);
        self::$string = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', 'self::make_web_ftp_clickable', self::$string);
        self::$string = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', 'self::make_email_clickable', self::$string);
        self::$string = trim(preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", self::$string));
    }

    //Smileys
    private static function make_smileys()
    {
        $files = get_files(basePath.'/inc/images/smileys',false,true,array('gif'));
        if(count($files) >= 1)
        {
            foreach($files as $file)
            {
                $bbc = preg_replace("=.gif=Uis","",$file);
                if(preg_match("=:".$bbc.":=Uis",self::$string) !== false)
                    self::$string = preg_replace("=:".$bbc.":=Uis",'<img src="../inc/images/smileys/'.$bbc.'.gif" alt="'.$bbc.'" />', self::$string);
            }
        }

        $var = array("/\ :D/", "/\ :P/","/\ ;\)/", "/\ :\)/", "/\ :-\)/", "/\ :\(/", "/\ :-\(/","/\ ;-\)/","/\ ^^/");
        $repl = array(' <img src="../inc/images/smileys/grin.gif" alt=":D" />',
                      ' <img src="../inc/images/smileys/zunge.gif" alt=":P" />',
                      ' <img src="../inc/images/smileys/zwinker.gif" alt="" />',
                      ' <img src="../inc/images/smileys/smile.gif" alt="" />',
                      ' <img src="../inc/images/smileys/smile.gif" alt="" />',
                      ' <img src="../inc/images/smileys/traurig.gif" alt="" />',
                      ' <img src="../inc/images/smileys/traurig.gif" alt="" />',
                      ' <img src="../inc/images/smileys/zwinker.gif" alt="" />',
                      ' <img src="../inc/images/smileys/^^.gif" alt="^^" />');
        self::$string = preg_replace($var,$repl, self::$string);
    }

    private static function make_glossar()
    {
        $txt = str_replace('&#93;',']',self::$string);
        $txt = str_replace('&#91;','[',$txt);

        // mark words
        for($s=0;$s<=count(self::$gl_words)-1;$s++)
        {
            $w = addslashes(regexChars(self::$gl_words[$s]));
            $txt = str_ireplace(' '.$w.' ', ' <tmp|'.$w.'|tmp> ', $txt);
            $txt = str_ireplace('>'.$w.'<', '> <tmp|'.$w.'|tmp> <', $txt);
            $txt = str_ireplace('>'.$w.' ', '> <tmp|'.$w.'|tmp> ', $txt);
            $txt = str_ireplace(' '.$w.'<', ' <tmp|'.$w.'|tmp> <', $txt);
        }

        // replace words
        for($g=0;$g<=count(self::$gl_words)-1;$g++)
        {
            $desc = regexChars(self::$gl_desc[$g]);
            $info = 'onmouseover="DZCP.showInfo(\''.jsconvert($desc).'\')" onmouseout="DZCP.hideInfo()"';
            $w = regexChars(html_entity_decode(self::$gl_words[$g]));
            $r = "<a class=\"glossar\" href=\"../glossar/?word=".self::$gl_words[$g]."\" ".$info.">".self::$gl_words[$g]."</a>";
            $txt = str_ireplace('<tmp|'.$w.'|tmp>', $r, $txt);
        }

        $txt = str_replace(']','&#93;',$txt);
        self::$string = str_replace('[','&#91;',$txt);
    }

    private static function bbcodetolow($founds)
    { return "[".strtolower($founds[1])."]".trim($founds[2])."[/".strtolower($founds[3])."]"; }

    /**
     * Führt den allgemeinen BBCode aus.
     *
     * @param string $string
     * @param boolean $htmlentities
     * @param boolean $nolinks
     * @return string
     */
    public static function parse_html($string='',$htmlentities=false, $nolinks=false)
    {
        self::$string = (string)string::decode($string);
        if(empty(self::$string)) return self::$string;
        self::$string = $htmlentities ? htmlentities(self::$string) : self::$string;
        self::$string = spChars_uml(self::$string);

        self::$string = preg_replace_callback("/\[(.*?)\](.*?)\[\/(.*?)\]/","self::bbcodetolow",self::$string);

        //Hide Tag
        self::$string = (checkme() >= 1 ? str_replace(array('[hide]','[/hide]'),'',self::$string) : preg_replace("/\[hide\](.*?)\[\/hide\]/", "",self::$string));

        // Badword Filter
        self::badword_filter();

        // Preappend http:// to url address if not present
        if(settings('urls_linked') && !$nolinks)
            self::make_clickable();

        self::$string = preg_replace('/\[url\=([^(http)].+?)\](.*?)\[\/url\]/i', '[url=http://$1]$2[/url]', self::$string);
        self::$string = preg_replace('/\[url\]([^(http)].+?)\[\/url\]/i', '[url=http://$1]$1[/url]', self::$string);

        // Add line breaks
        self::$string = nl2br(self::$string);

        // Remove the trash made by previous
        self::$string = preg_replace(self::$lineBreaks_search, self::$lineBreaks_replace, self::$string);

        // Parse bbcode
        self::$string = preg_replace(self::$simple_search, self::$simple_replace, self::$string);
        self::$string = API_CORE::run_additional_bbcode(self::$string,false);

        // Parse Players
        self::$string = preg_replace(self::$vid_search,self::$vid_replace,self::$string);

        // Parse Smileys
        self::make_smileys();

        // Parse Glossar
        if(glossar_enabled && self::$use_glossar)
            self::make_glossar();

        // Parse [list] tags
        self::$string = preg_replace('/\[list\](.*?)\[\/list\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', self::$string);

        return preg_replace('/\[list\=(disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-greek|lower-alpha|lower-latin|upper-alpha|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha|none)\](.*?)\[\/list\]/sie',
                '"<ol style=\"list-style-type: $1;\">\n".self::process_list_items("$2")."\n</ol>"', self::$string);
    }

    /**
     * Führt den BBCode des TS3 Servers aus.
     *
     * @param string $string
     * @return string
     */
    public static function parse_ts3($string='')
    {
        self::$string = (string)$string;
        if(empty(self::$string)) return self::$string;

        // Badword Filter
        self::badword_filter();

        self::$string = preg_replace('/\[url\=([^(http)].+?)\](.*?)\[\/url\]/i', '[url=http://$1]$2[/url]', self::$string);
        self::$string = preg_replace('/\[url\]([^(http)].+?)\[\/url\]/i', '[url=http://$1]$1[/url]', self::$string);

        // Remove the trash made by previous
        self::$string = preg_replace(self::$lineBreaks_search, self::$lineBreaks_replace, self::$string);

        // Parse bbcode
        self::$string = preg_replace(self::$simple_search, self::$simple_replace, self::$string);

        // Parse [list] tags
        self::$string = preg_replace('/\[list\](.*?)\[\/list\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', self::$string);
        return preg_replace('/\[list\=(disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-greek|lower-alpha|lower-latin|upper-alpha|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha|none)\](.*?)\[\/list\]/sie',
                '"<ol style=\"list-style-type: $1;\">\n".self::process_list_items("$2")."\n</ol>"', self::$string);
    }

    //-> Textteil in Zitat-Tags setzen
    public static function zitat($nick,$zitat)
    {
        $zitat = str_replace(chr(145), chr(39), $zitat);
        $zitat = str_replace(chr(146), chr(39), $zitat);
        $zitat = str_replace("'", "&#39;", $zitat);
        $zitat = str_replace(chr(147), chr(34), $zitat);
        $zitat = str_replace(chr(148), chr(34), $zitat);
        $zitat = str_replace(chr(10), " ", $zitat);
        $zitat = str_replace(chr(13), " ", $zitat);
        $zitat = preg_replace("#[\n\r]+#", "<br />", $zitat);
        return '<div class="quote"><b>'.$nick.' '._wrote.':</b><br />'.string::decode($zitat).'</div><br /><br /><br />';
    }

    public static function nletter($txt)
    { return '<style type="text/css">p { margin: 0px; padding: 0px; }</style>'.$txt; }

    public static function use_glossar($var=true)
    { self::$use_glossar = $var; }
}
