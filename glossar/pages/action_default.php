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
    bbcode::use_glossar(false); $glword_sql = '';
    if(isset($_GET['word']) || isset($_GET['bst']))
    {
        if(isset($_GET['word']) && !empty($_GET['word']))
            $glword_sql = "WHERE word = '".string::encode($_GET['word'])."' OR word LIKE '".string::encode(substr($_GET['word'],0,1))."%' ";
        elseif(isset($_GET['bst']) && !empty($_GET['bst']) && $_GET['bst'] != 'all')
            $glword_sql = "WHERE word LIKE '".string::encode($_GET['bst'])."%' ";
    }

    $qry = db("SELECT * FROM ".dba::get('glossar')." ".$glword_sql." ORDER BY word"); $color = 1; $show = '';
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $class = (isset($_GET['word']) && $_GET['word'] == $get['word'] ? 'highlightSearchTarget' : '');
        $show .= show($dir."/glossar_show", array("word" => string::decode($get['word']), "class" => $class, "glossar" => bbcode::parse_html($get['glossar'])));
    }

    $bsta = array(_all,"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); $abc = '';
    foreach ($bsta as $bst)
    {
        $bclass = ((((isset($_GET['bst']) && !empty($_GET['bst']) && strtolower($bst) == strtolower($_GET['bst'])) || (isset($_GET['bst']) && $_GET['bst'] == 'all' && $bst == _all)) || (empty($_GET['bst']) || !isset($_GET['bst'])) && $bst == _all) ? 'fontWichtig' : '');
        $ret = ($bst == _all) ? '?bst=all' : "?bst=".$bst;
        $abc .= "<a href=\"".$ret."\" title=\"".$bst."\"><span class=\"".$bclass."\">".$bst."</span></a> ";
    }

    $index = show($dir."/glossar", array("abc" => $abc, "show" => $show));
}