<?php

/*
 * postfix_domainlist.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;
if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Postfix Domain List, host: $0',$postfixhost);

if(!isset($_GET['o']))
	$SESSION->restore('clo', $o);
else
	$o = $_GET['o'];
$SESSION->save('clo', $o);

if(!isset($_GET['searchdomain']))
	$SESSION->restore('clsearchdomainl', $searchdomain);
else
	$searchdomain = $_GET['searchdomain'];
$SESSION->save('clsearchdomain', $searchdomain);
		
if (! isset($_GET['page']))
	$SESSION->restore('clp', $_GET['page']);

$domainlist = $POSTFIX->GetDomains($o, $searchdomain);
$listdata['total'] = $domainlist['total'];
$listdata['direction'] = $domainlist['direction'];
$listdata['searchdomain'] = $searchdomain;

unset($domainlist['total']);
unset($domainlist['direction']);

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!$CONFIG['phpui']['customerlist_pagelimit'] ? $domaindata['total'] : $CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('clp', $page);

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('domainlist',$domainlist);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('postfix_domainlist.html');

?>
