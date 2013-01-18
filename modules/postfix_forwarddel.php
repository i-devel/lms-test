<?php

/*
 * postfix_domaindel.php v0.0.1 by Sylwester Zdanowski
 */

if(!$POSTFIX->Connection()){ $SMARTY->display('postfix_noserver.html'); die();}

if($_GET['is_sure']=="1" && $_GET['source'])
{
		$POSTFIX->ForwardDelete($_GET['source']);
}

$SESSION->redirect('?m=postfix_forwardlist');

?>
