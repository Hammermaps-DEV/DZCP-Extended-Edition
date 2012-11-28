<?php
#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
if (_version < 1.0) //Mindest Version pruefen
	$index = _version_for_page_outofdate;
else
{
	if(permission("intnews"))
	{
		$intern = "WHERE public = 1";
		$intern2 = "WHERE intern = 1 OR intern = 0 AND datum <= ".time()." AND public = 1";
	}
	else 
	{
		$intern = "AND intern = 0 AND public = 1";
		$intern2 = "WHERE intern = 0 AND datum <= ".time()." AND public = 1";
	}
	    
	$page = (isset($_GET['page']) ? $_GET['page'] : 1);
	$n_kat = (empty($kat) ? '' : "AND kat = '".$kat."'");
	$qry = db("SELECT id,titel,autor,datum,kat,text FROM ".$db['news']." ".$intern2." ".$n_kat." ORDER BY datum DESC LIMIT ".($page - 1)*$maxarchivnews.",".$maxarchivnews."");
	$entrys = cnt($db['news'], " ".$intern2." ".$n_kat);
	    
	$color = 1; $show = '';
	while($get = _fetch($qry))
	{
		$getk = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
		$comments = cnt($db['newscomments'], " WHERE news = ".$get['id']."");
		$titel = show(_news_show_link, array("titel" => cut(re($get['titel']),$lnewsarchiv), "id" => $get['id']));
		$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
		$show .= show($dir."/archiv_show", array("autor" => autor($get['autor']), 
		                                                 "date" => date("d.m.y", $get['datum']), 
		                                                 "titel" => $titel, 
		                                                 "class" => $class, 
		                                                 "kat" => re($getk['kategorie']), 
		                                                 "comments" => $comments));
	}
	    
	$nav = nav($entrys,$maxarchivnews,"?action=archiv");
	$index = show($dir."/archiv", array("head" => _news_archiv_head,
	                                        "date" => _datum,
	                                        "titel" => _titel,
	                                        "nav" => $nav,
	                                        "kat" => _news_admin_kat,
	                                        "show" => $show,
	                                        "autor" => _autor));
}
?>