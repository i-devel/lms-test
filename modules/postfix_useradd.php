<?php

/*
 * postfix_domainadd.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;
if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

$layout['pagetitle'] = trans('Add Postfix User, host: $a',$postfixhost);

$error='';

if(isset($_POST['useradd']))
{
	$useradd = $_POST['useradd'];
	unset($_POST['useradd']);
	if(!$useradd['password'])
	{
		$error['password']='Enter password';
	}
	if(!check_email($useradd['email']))
	{
		$error['email']='E-mail isn\'t correct';
	}

	if(!$error)
	{
		$POSTFIX->AddUser($useradd);
		if(!isset($useradd['reuse']))
		{
			$SESSION->redirect('?m=postfix_userslist');
		}	
		unset($useradd);	
	}
}

$SMARTY->assign('useradd', $useradd);
$SMARTY->assign('error', $error);
$SMARTY->display('postfix_useradd.html');
?>
