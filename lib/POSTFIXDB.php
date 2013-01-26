<?php

#POSTFIXDB.php by Sylwester Zdanowski

define('LMSDB_DIR', dirname(__FILE__));

require_once(LMSDB_DIR.'/LMSDB_common.class.php');

function PDBInit($DBPOSTFIXA, $DBPOSTFIXB)
{
	global $postfixhost;

    $dbtype = strtolower($DBPOSTFIXA['type']);

	if (!file_exists(LMSDB_DIR."/LMSDB_driver_$dbtype.class.php") )
		return 0;
	else {

		require_once(LMSDB_DIR."/LMSDB_driver_$dbtype.class.php");
		$drvname = "LMSDB_driver_$dbtype";
		$postfixhost=$DBPOSTFIXA['host'];
		$DB = new $drvname($DBPOSTFIXA['host'], $DBPOSTFIXA['user'], $DBPOSTFIXA['password'], $DBPOSTFIXA['database']);

		if (!$DB->_loaded)
			return 0;
		else if (!$DB->_dblink){
		$postfixhost=$DBPOSTFIXB['host'];
		$DB = new $drvname($DBPOSTFIXB['host'], $DBPOSTFIXB['user'], $DBPOSTFIXB['password'], $DBPOSTFIXB['database']);

		if (!$DB->_loaded){
			return 0;}
		else if (!$DB->_dblink){
			return 0;}
		else {
            // set client encoding
            $DB->SetEncoding('UTF8');
			return $DB;
	    }
		}
		else {
            // set client encoding
            $DB->SetEncoding('UTF8');
			return $DB;
	    }
    }
	return FALSE;
}

?>
