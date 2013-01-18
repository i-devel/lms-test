<?php

/*
 * postfix_domaindel.php v0.0.1 by Sylwester Zdanowski
 */

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

if($_GET['is_sure']=="1" && $_GET['email'])
{
		$POSTFIX->UserDelete($_GET['email']);
}

$SESSION->redirect('?m=postfix_userslist');

?>
