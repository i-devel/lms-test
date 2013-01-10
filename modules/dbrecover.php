<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

function DBLoad($filename=NULL)
{
	global $DB, $CONFIG;

	if(!$filename)
		return FALSE;
	$finfo = pathinfo($filename);
	$ext = $finfo['extension'];

	if ($ext == 'gz' && extension_loaded('zlib'))
		$file = gzopen($filename,'r'); //jezeli chcemy gz to plik najpierw trzeba rozpakowac
	else
		$file = fopen($filename,'r');

	if (!$file) return FALSE;

	$DB->BeginTrans(); // przyspieszmy działanie jeżeli baza danych obsługuje transakcje
	while(!feof($file))
	{
		$line = fgets($file, 8192);
		if($line!='')
		{
			$line = str_replace(";\n", '', $line);
			$DB->Execute($line);
		}
	}
	$DB->CommitTrans();

	if ((extension_loaded('zlib'))&&($ext=='gz'))
		gzclose($file);
	else
		fclose($file);

	// Okej, zróbmy parę bzdurek db depend :S
	// Postgres sux ! (warden)
	// Tak, a łyżka na to 'niemożliwe' i poleciała za wanną potrącając bannanem musztardę (lukasz)

	switch($CONFIG['database']['type'])
	{
		case 'postgres':
			// actualize postgres sequences ...
			foreach($DB->ListTables() as $tablename)
				// ... where we have *_id_seq
				if(!in_array($tablename, array(
							'rtattachments',
							'dbinfo',
							'invoicecontents',
							'receiptcontents',
							'documentcontents',
							'stats',
							'eventassignments',
							'monitnodes',
							'monituser',
							'hv_county',
							'hv_province',
							'hv_borough',
							'hv_pcb',
							'hv_pricelist',
							'hv_pstn',
							'hv_pstnrange',
							'hv_pstnusage',
							'hv_subscriptionlist',
							'hv_terminal',
							'hv_customers',
							'hv_enduserlist',
							'sessions')))
					$DB->Execute("SELECT setval('".$tablename."_id_seq',max(id)) FROM ".$tablename);
		break;
	}
}

if(isset($_GET['is_sure']))
{
	set_time_limit(0);

	if (!empty($_GET['gz']))
		$LMS->DatabaseCreate(TRUE, FALSE);
	else
		$LMS->DatabaseCreate(FALSE, FALSE);

	$db = $_GET['db'];

	if(file_exists($CONFIG['directories']['backup_dir'].'/iNET-lms-'.$db.'.sql'))
	{
		DBLoad($CONFIG['directories']['backup_dir'].'/iNET-lms-'.$db.'.sql');
	}
	elseif (extension_loaded('zlib') && file_exists($CONFIG['directories']['backup_dir'].'/iNET-lms-'.$db.'.sql.gz'))
	{
		DBLoad($CONFIG['directories']['backup_dir'].'/iNET-lms-'.$db.'.sql.gz');
	}

    include(MODULES_DIR . '/dblist.php');
//	$SESSION->redirect('?m='.$SESSION->get('lastmodule'));
}
else {
	$layout['pagetitle'] = trans('Database Backup Recovery');
	$SMARTY->display('header.html');
	echo '<H1>'.trans('Database Backup Recovery').'</H1>';
	echo '<P>'.trans('Are you sure, you want to recover database created at $a?', date('Y/m/d H:i.s',$_GET['db'])).'</P>';
	echo '<A href="?m=dbrecover&db='.$_GET['db'].'&is_sure=1">'.trans('Yes, I am sure.').'</A>';
	$SMARTY->display('footer.html');
}

?>
