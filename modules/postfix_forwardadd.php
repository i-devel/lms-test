<?php

/*
 * postfix_forwardadd.php v0.0.1 by Sylwester Zdanowski
 */
global $postfixhost;
if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

$layout['pagetitle'] = trans('Add Postfix Forwarding, host: $a',$postfixhost);

if(isset($_POST['forwardadd']))
{
	$forwardadd=$_POST['forwardadd'];
	unset($_POST['forwardadd']);
	if(!$forwardadd['destination'])
	{
		$error['destination']='Destination can\'t be empty';
	}
	if(!$forwardadd['source'])
	{
		$error['source']='Source can\'t be empty';
	}
	else
	{
		$find =$POSTFIX_DB->GetOne('SELECT 1 FROM forwardings WHERE source LIKE ?',array($forwardadd['source']));
		if($find==1)
		{
			$error['source'] = trans('source exists!');	
		}
	}
	
	if(!$error)
	{
		$POSTFIX->AddForward($forwardadd['source'], $forwardadd['destination']);
		if(!isset($forwardadd['reuse']))
		{
			$SESSION->redirect('?m=postfix_forwardlist');
		}
		unset($forwardadd);
	}
}

$SMARTY->assign('forwardadd', $forwardadd);
$SMARTY->assign('error', $error);
$SMARTY->display('postfix_forwardadd.html');

?>
