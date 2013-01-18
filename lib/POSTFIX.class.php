<?php


/*****************************************************************************
*                                                                            *
* LMS POSTFIX version 0.0.2                                                  *
*                                                                            *
* (c) 2012 Copyright by Sylwester Zdanowski                                  *
* www    : www.pcpomoc.infolan.net.pl                                        *
* e-mail : szdanowski@infoaln.net.pl                                         *
* gg     :                                                                   *
*                                                                            *
* $Id: POSTFIX.class.php, v0.0.2 Exp $                                       *
*                                                                            *
******************************************************************************/


class POSTFIX{
	var $iduser;
	var $AUTH;
	var $LMS;
	var $DB;
	var $CONFIG;
	var $version = '0.0.2';
        private $connection=1;

        function POSTFIX(&$LMS,&$DB,&$AUTH,&$CONFIG)
        {
                $this->LMS = &$LMS;
                $this->DB = &$DB;

                $this->AUTH = &$AUTH;
                $this->CONFIG = &$CONFIG;
                $this->iduser = $this->AUTH->id;
		if(!$this->DB)
		{
			$this->connection=0;
		}
        }

	function Connection()
	{
		return $this->connection;
	}

	function _version()
	{
		return $this->version;
	}
#################################DOMAIN#############################
	function AddDomains($name)
	{
		return $this->DB->Execute('INSERT INTO domains (domain) VALUE(?)',array($name));
	}

	function DomainDelete($name)
	{
		return $this->DB->Execute('DELETE FROM domains WHERE domain LIKE ?',array($name));
	}

	function GetDomains($direction='asc', $searchdomain=NULL)
	{

		($direction != 'desc') ? $direction = 'asc' : $direction = 'desc';

		$domainlist=$this->DB->GetAll('SELECT * FROM domains '
				.($searchdomain ? ' WHERE domain LIKE "%'.$searchdomain.'%"' : '')
				.($direction ? ' ORDER BY domain '.$direction : ''));

		$domainlist['total'] = sizeof($domainlist);
		$domainlist['direction'] = $direction;

		return $domainlist;
	}
###############################FORWARDINGS################################################
	function AddForward($source, $destination)
	{
		 return $this->DB->Execute('INSERT INTO forwardings (source,destination) VALUE(?,?)',array($source,$destination));
	
	}

	function GetForwardList($order='source,asc',$source=NULL,$destination=NULL)
	{

		list($order,$direction) = sscanf($order, '%[^,],%s');
		($direction != 'desc') ? $direction = 'asc' : $direction = 'desc';	

		switch($order)
		{
			case 'source':
				$sqlord = ' ORDER BY source';
			break;
			case 'destination':
				$sqlord = ' ORDER BY destination';
			break;
		}

		$forwardingslist=$this->DB->GetAll('SELECT * FROM forwardings WHERE 1=1'
		.($source ? ' AND source LIKE "%'.$source.'%"' : '')
		.($destination ? ' AND destination LIKE "%'.$destination.'%"' : '')
		.($sqlord !='' ? $sqlord.' '.$direction:''));

		$forwardingslist['total'] = sizeof($forwardingslist);
		$forwardingslist['order'] = $order;
		$forwardingslist['direction'] = $direction;

		return $forwardingslist;
	}

	function ForwardDelete($source)
	{
		return $this->DB->Execute('DELETE FROM forwardings WHERE source LIKE ?',array($source));
	}
#################################USER##########################################
	function AddUser($useradd)
	{
		return $this->DB->Execute('INSERT INTO users (email, password) VALUE(?, ?)',
					array($useradd['email'],$useradd['password']));
	}

	function GetUsersList($direction='asc', $email=NULL)
	{
		($direction != 'desc') ? $direction = 'asc' : $direction = 'desc';

		$userslist=$this->DB->GetAll('SELECT * FROM users '
		.($email ? ' WHERE email LIKE "%'.$email.'%"' : '')
		.($direction ? ' ORDER BY email '.$direction : ''));

		$userslist['total'] = sizeof($userslist);
		$userslist['direction'] = $direction;

		return $userslist;
	}

	function UserDelete($email)
	{
		return $this->DB->Execute('DELETE FROM users WHERE email LIKE ?',array($email));
	
	}

	function UpdateUser($useredit)
	{
		return $this->DB->Execute('UPDATE users SET email =?, password=? WHERE email LIKE ?',
			array($useredit['email'],$useredit['password'],$useredit['identifier']));
	
	}
}
