<?php
/**
*  This file is a class of ReporterGUI
*
*  @author itpao25
*  ReporterGUI Web Interface  Copyright (C) 2015
*
*  This program is free software; you can redistribute it and/or
*  modify it under the terms of the GNU General Public License as
*  published by the Free Software Foundation; either version 2 of
*  the License, or (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  @package ReporterGUI
*/

/**
* Notify system for web interface
* If you see bugs, let me know!
* tropaolo@gmail.com
* @since 1.5
*/
require_once("inc/heart.inc.php");

// Check user is logged
if($RGWeb->isLogged() == false) {
	print "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
	die("You must be logged in to view this page!");
}

/* Header for json data output */
header('Content-Type: application/json');

if(isset($_GET['uid']) && isset($_GET['action'])):

  switch ($_GET['action']) {
    case 'notify':

			if(!intval($_GET['uid'])) die();
			/* Check get id is equal for user id logged */
			if($RGWeb->getIDLogged() != $_GET['uid']) die();

			$idLogged = $RGWeb->getIDLogged();
			/**
			* Get current for user logged
			*/
			function currentDataUser() {
				global $RGWeb, $idLogged;
				// Get current data in json
				// Return with json for user in mysql
				$json_current = $RGWeb->runQueryMysql("SELECT data,ID FROM `webinterface_login` WHERE ID={$idLogged}");
				$json_respose = $json_current->fetch_array();
				// Json
				$data = $json_respose[ "data" ];
				return json_decode($data, true);
			}

			/**
			* Get current data for all server
			*/
			function currentDataServer() {
				global $RGWeb;
				// Check last report con lo stato = 1
				$query = $RGWeb->runQueryMysql("SELECT * FROM `reporter` WHERE status='1' ORDER BY ID DESC LIMIT 0,5");
				$intNum = $query->num_rows;
				return $query;
			}
			$currentDataServer = currentDataServer();

      $arrayNew = array("report" => array());
			$arrayResult = array("result" => array());

			// New report int
			$newint = 0;

			// Cerco la segnalazione nella data salvata per gli utenti
			$int = 1;

			// Cerco i nuovi report
			for($i = 1; $row = $currentDataServer->fetch_array(); $i++) {

				// ID del report
				$id = $row[ "ID" ];
				$time = $row[ "Time" ];
				$server = $row[ "server" ];
				$player = $row[ "PlayerReport" ];
				$reason = $row[ "Reason" ];

				foreach (currentDataUser() as $rowa)
				{
					if(!isset($rowa[$id])) {
						// Found new report
						$arrayResult[ "result" ] [ $int ] = array("ID" => $id, "view" => true,"time" => $time,"server" => $server, "player" => $player, "reason" => $reason);
						$newint++;
					}
					$int++;
				}

				// No reports new found
				if($newint == 0) {
					$arrayResult[ "result" ] [ "not-found" ] = true;
				}

				$arrayNew[ "report" ] [ $id ] = "true";
			}

			/* Update json status for user */
			function uploadDataUser() {
				global $RGWeb, $arrayNew, $idLogged;
				// Render new json converter
				$json = json_encode($arrayNew);
				$query = $RGWeb->runQueryMysql("UPDATE `webinterface_login` SET `data` = '{$json}' WHERE `webinterface_login`.`ID` ={$idLogged};");
			}
			uploadDataUser();

			// Output data
			$json_string = json_encode($arrayResult, JSON_PRETTY_PRINT);
			print $json_string;

			break;
    default:
      # code...
      break;
  }

endif;

?>
