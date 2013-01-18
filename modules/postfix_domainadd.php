<?php

/*
 * postfix_domainadd.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}


$layout['pagetitle'] = trans('Add Postfix Domain, host: $a',$postfixhost);

if(isset($_POST['domainadd']))
{
	$domainadd=$_POST['domainadd'];

	$find =$POSTFIX_DB->GetOne('SELECT 1 FROM domains WHERE domain LIKE ?',array($domainadd['name']));
	if($find==1)
	{ 	
		$error['name'] = trans('Domain exists!');
		$SMARTY->assign('error', $error);
		$SMARTY->assign('name', $domainadd['name']);
	}
	else
	{
		$POSTFIX->AddDomains($domainadd['name']);
		if(!isset($$domainadd['reuse']))
		{
			$SESSION->redirect('?m=postfix_domainlist');
		}
	}
}

$SMARTY->display('postfix_domainadd.html');
?>
