<?php

/*
 * postfix_forwardlist.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Postfix Forwarding List, host $a',$postfixhost);

if(!isset($_GET['o']))
	$SESSION->restore('clo', $o);
else
	$o = $_GET['o'];
$SESSION->save('clo', $o);

if(!isset($_GET['source']))
	$SESSION->restore('clsource', $source);
else
	$source = $_GET['source'];
$SESSION->save('clsource', $source);

if(!isset($_GET['destination']))
	$SESSION->restore('cldestination', $destination);
else
	$destination = $_GET['destination'];
$SESSION->save('cldestination', $destination);
		
if (! isset($_GET['page']))
	$SESSION->restore('clp', $_GET['page']);

$forwardlist = $POSTFIX->GetForwardList($o, $source, $destination);
$listdata['total'] = $forwardlist['total'];
$listdata['order'] = $forwardlist['order'];
$listdata['direction'] = $forwardlist['direction'];
$listdata['source'] = $source;
$listdata['destination'] = $destination;

unset($forwardlist['total']);
unset($forwardlist['order']);
unset($forwardlist['direction']);

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!$CONFIG['phpui']['customerlist_pagelimit'] ? $domaindata['total'] : $CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('clp', $page);

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('forwardlist',$forwardlist);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('postfix_forwardlist.html');

?>
