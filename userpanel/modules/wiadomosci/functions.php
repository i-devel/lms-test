<?php

/**************************************\
| Wiadomości Administracyjne v 2.0     |
| (c)2012 by Sylwester Kondracki       |
| www.lmsdodatki.pl                    |
\**************************************/

function module_main()
{
    global $DB,$LMS,$SESSION,$SMARTY;
    
    if (isset($_GET['ar'])) $archiwum = true; else $archiwum = false;
    if(isset($_GET['p']))
    {
	$id = (int)$_GET['p'];
	$DB->Execute('UPDATE messageitems SET isread = ? WHERE id = ?;',array(1,$id));
    }
    
    
    $message = $DB->GetAll('SELECT mi.id, m.subject, m.body, m.cdate, mi.isread , mi.firstread 
		FROM messageitems mi, messages m
		WHERE m.id = mi.messageid AND m.type=? AND mi.customerid = ? AND mi.status = ? '
		.(!$archiwum ? ' AND mi.isread=\'0\'' : '')
		.' ORDER BY m.cdate DESC ;',array(MSG_USERPANEL,$SESSION->id,MSG_SENT));
    
    if ($message) {
	$czas = time();
	for ($i=0; $i<sizeof($message); $i++)
	    if (!$message[$i]['isread'])
	    {
	    $DB->Execute('UPDATE messageitems SET '
	    .(!$message[$i]['firstread'] ? ' firstread=\''.$czas.'\', ' : '') 
	    .' lastread=\''.$czas.'\' WHERE id = ?;',array($message[$i]['id']));
	}
    }
    
    $count = count($message);
//    if ($count!==0) for ($i=0;$i<$count;$i++) $message[$i]['body'] = base64_decode($message[$i]['body']);
    $SMARTY->assign('archiwum',$archiwum);
    $SMARTY->assign('message', $message);

    $SMARTY->display('module:wiadomosci.html');
}

?>
