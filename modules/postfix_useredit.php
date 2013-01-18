<?php
#SNMP by Sylwester Zdanowski v0.1

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

if($_GET['save']==1)
{
	$useredit=$_POST['useredit'];
	if(!$useredit['email'])
		$error['name']='Snmp name cannot be empty!';
	if(!$useredit['password'])
		$error['deviceid']='Device id cannot be empty!';
print "dasd";
	if(!$error)
	{ print "----";
		$POSTFIX->UpdateUser($useredit);
		$SESSION->redirect('?m=postfix_userslist');
	}
}

$layout['pagetitle'] = trans('Postfix Edit User: $a',$_GET['email']);

$useredit=$POSTFIX_DB->GetRow('SELECT email, password FROM users WHERE email LIKE "'.$_GET['email'].'"');

$SMARTY->assign('useredit',$useredit);
#$SMARTY->assign('snmp',$snmp);
$SMARTY->display('postfix_useredit.html');

?>
