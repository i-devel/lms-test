<?php

/*
 * postfix_forwardlist.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('User List, host: $a',$postfixhost);

if(!isset($_GET['o']))
	$SESSION->restore('clpo', $o);
else
	$o = $_GET['o'];
$SESSION->save('clo', $o);

if(!isset($_GET['searchmail']))
	$SESSION->restore('clsearchmail', $searchmail);
else
	$searchmail = $_GET['searchmail'];
$SESSION->save('clsearchmail', $searchmail);
		
if (! isset($_GET['page']))
	$SESSION->restore('clp', $_GET['page']);

$userslist = $POSTFIX->GetUsersList($o, $searchmail);
$listdata['total'] = $userslist['total'];
$listdata['direction'] = $userslist['direction'];
$listdata['order'] = $userslist['order'];
$listdata['email'] = $searchmail;

unset($userslist['total']);
unset($userslist['state']);
unset($userslist['order']);
unset($userslist['direction']);

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!$CONFIG['phpui']['customerlist_pagelimit'] ? $domaindata['total'] : $CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('clp', $page);

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('userslist',$userslist);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('postfix_userslist.html');

?>
