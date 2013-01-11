<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  $Id: v 1.00 2013/01/11 13:01:35 Sylwester Kondracki Exp $
 */




// -------------------------------- PLIK PRZYKŁADOWY ----------------------------------------------





$apifile = dirname(__FILE__).'/LMS.apiclient.class.php';  // ścieżka do klasy

if (file_exists($apifile)) require_once($apifile); else die('brak pliku '.$apifile);

// login, hasło, domena, klucz szyfrujacy dane, url, port - jeżeli np. mam to na virtualce
$API = new LMS_API_CLIENT('login','haslo','domena','klucz_szyfrujacy','http://www.moj_lms.pl/api.php',NULL);

$API->InitRequest(); // inicjujemy

// $z1, $z2 itd to jest ID zadanego requesta,
$z1 = $API -> AddRequest('getcustomerinfo',array('id'=>'3')); // zadajemy pytania 
$z2 = $API -> AddRequest('getuserinfo',array('id'=>'1'));
$z3 = $API -> AddRequest('getremote');

$API->Send(); // wysyłamy żądanie do serwera
$result = $API->GetResult(); // pobieramy dane do tablicy

echo 'getremote : '.$result[$z3].'<br><br>'; // odczyt konkretnego requesta
echo "<pre>"; print_r($result); echo "</pre>"; // wysietlamy cala tablice

// a dalej już robimy z wynikiem co chcemy

?>