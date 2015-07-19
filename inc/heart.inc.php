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

/*
	Classe principale per la gestione delle pagine
	Main class for the management of pages

	Questo progetto è creato in italia, molti commenti sono stati scritti in italiano per
	permettere di gestire meglio il codice

	This project is created in Italy, many comments were written in Italian for
	allow to better manage the code


	Cose da fare:

	- Finire il sistema di edit server
	- Gestione degli utenti con i permessi
	- V Gestire l'homepage facendo vedere la lista dei server con i last reports
	- Creare il file view-report.php con i parametri get per visualizzare / eliminare una segnalazione
	- Gestire i menu attraverso i permessi degli utenti

 */

define("RG_ROOT", "true", true);
define("RG_INSTALL", dirname(dirname(__FILE__)). "/install", true);

Class ReporterGUI
{
	/**
	* Get utility globale per la classe
	*/
	public $getUtily;
	private $mysqli = false;

	function __construct($loadClass = null) {

		$load = new loadClass();
		$this->getUtily = $load->getUtily();

		/* Controllo se è stato installato nel mysql */
		$this->makeDB();
		$this->openConMysql();


		/* Start the session global */
		session_start();
	}

	function __destruct() {
		/* Chiudo la connessione al database */
		//$this->mysqli->close();
	}

	/**
	* Mysql management
	*/
	public function openConMysql() {

		$mysql_host = $this->getConfig("mysql-host");
		$mysql_user = $this->getConfig("mysql-user");
		$mysql_password = $this->getConfig("mysql-password");
		$mysql_namedb = $this->getConfig("mysql-databaseName");
		$mysql_getPort = $this->getConfig("mysql-port");

		$this->mysqli = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_namedb, $mysql_getPort) or die("Errore durante la connessione " . mysqli_error($con));

		//if(!$this->mysqli == false) die("Errore nello stabilire una connessione al database");

		return $this->mysqli;

	}

	/**
	* Checks if a folder exist
	*/
	private function folder_exist($folder) {
		$foldercheck = realpath($folder);
		return ($foldercheck !== false AND is_dir($foldercheck)) ? true : false;
	}

	/**
	* Funzione che controlla se la cartella install è presente, dopo aver controllato se
	* la tabella webinterface_servers è presente
	* Controllo se è stato revisionato il file di configurazione per la prima volta
	*
	*	Heart platform to check web hosting is set
	* For safety issue should be removed mandatorily
	*/
	public function makeDB() {

		$val = $this->getConfig("installed-sec");
		if($val == false)
			$this->getUtily->messageInstall();
		else

			if($this->mysqli == false) $this->openConMysql();

			$query = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_servers'");
			$query2 = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_login'");

			if($query->num_rows !=1 || $query2->num_rows !=1) {

				if($this->folder_exist(RG_INSTALL))
					print "<meta http-equiv=\"refresh\" content=\"0;URL=install/\">";

			} else {

				if($this->folder_exist(RG_INSTALL))
					$this->getUtily->messageInstallFolder();

			}
	}
	/**
	* Run query from mysql
	*/
	public function runQueryMysql($query) {
		return $this->mysqli->query($query);
	}

	/* Escape string utilty */
	public function real_escape_string($str)
 	{
    return $this->mysqli->real_escape_string($str);
	}

	/* Pulisco da tag html */
	public function escape_html($str) {
		return strip_tags($str);
	}

 	/**
	* Get default header
	*
	* @param Pos Position on the page request
	* @return Structure of the template header
	*/
	public function getHeader($Pos, $menu = null) {
		require_once("header.inc.php");
	}

	/**
	* Get default footer
	*
	* @return Structure of the template footer
	*/
	public function getFooter() {
		require_once("footer.inc.php");
	}

	/* Function public to get Dashboard page
	*/
	public function getHomeIndex() {

		$this->getHeader("Dashboard");
		require_once("index.inc.php");
		$this->getFooter();

	}

	/* Function public to get Login page */
	public function getLoginIndex() {
		require_once("login-include.php");
	}

	/**
	* Function for page Server List (server-list.php)
	*/
	public function getServerList() {

		$list = $this->runQueryMysql("SELECT * FROM `webinterface_servers`");

		print "
		<table style=\"width:100%\">
			<thead>
				<tr>
					<td><b>Name</b></td>
					<td><b>Total report</b></td>
					<td><b>Complete</b></td>
					<td><b>Waiting</b></td>
				</tr>
			</thead>";

		$num = $list->num_rows;

		if($num == 0)
			print "<tr class=\"list-server-table\" data-href=\"add-server.php\">
			<td >No server present, add one?</td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";

		while($row = $list->fetch_array()) {

			// Process each row
			// Valori presi dalla tabella `reporter` utilizzando le funzioni pubbliche
			print "
			<tr class=\"list-server-table\" data-href=\"edit-server.php?name={$row['name']}\">
				<td>{$row['name']}</td>
				<td>{$this->getTotalReport($row['name'])}</td>
				<td>{$this->getCompleteReport($row['name'])}</td>
				<td>{$this->getWaitingReport($row['name'])}</td>
			</tr>";

		}

		print "</table>";

		//include(dirname(dirname(__FILE__)).'/server-list.php');
	}

	/**
	* Get number of total report for server
	*
	* @param name
	* @return int
	*/
	public function getTotalReport($name) {
		$int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."'");
		return $int->num_rows;
	}

	/**
	* Get number of report complete for server
	*
	* @param name
	* @return int
	*/
	public function getCompleteReport($name) {
		$int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."' AND status='2'");
		return $int->num_rows;
	}

	/**
	* Get number of report Waiting
	* @param name
	* @return int
	*/
	public function getWaitingReport($name) {
		$int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."' AND status='1'");
		return $int->num_rows;
	}

	/**
	* Lista dei server da visualizzare in homepage
	*/
	public function getListIndex() {

		$list = $this->runQueryMysql("SELECT * FROM `webinterface_servers`");

		$head = "
		<div class=\"list-server-dash\">";
		$foo = "
		</div>";

		$totali = $list->num_rows;

		for($i = 1; $row = $list->fetch_array(); $i++) {

			/* Controllo se il valore del ciclo for è 1 oppure multiplo di 4, perchè deve essere usato come head */
			if($i == 1 || $i % 4 == 0):
				print $head;
			endif;

			print "
			<div class=\"container-list-server\">";

			print "
				<div class=\"list-server-dash_name\">
					<h3>{$row['name']}
						<span class=\"edit-serverlist-dash\">
							<a href=\"edit-server.php?name={$row['name']}\">
								<i class=\"fa fa-pencil\"></i>
							</a>
						</span>
					</h3>";

			/* Uso la funzione per convertire gli spazi in <br /> tag html */
			print nl2br("Total report: <b>{$this->getTotalReport($row['name'])}</b>
				Report resolved: <b>{$this->getCompleteReport($row['name'])}</b>
				Waiting report: <b>{$this->getWaitingReport($row['name'])}</b>");

			$this->getLastReportHtmlIndex($row['name'], 5);
			print "
				</div>
			</div>";


			/* Fix il problema del numero dei server, in caso il numero totale non è multiplo di 3 */

			// Se è all'ultimo row ma non multiplo di 3
			// Quindi 3 / 2 disponibili

			if($i == $totali && $i % 3 != 0) {
				print "
				<div class=\"container-list-server\"></div>";
				$i++;

				// Controllo se aggiungendo comunque un campo resta non multiplo di 3
				// Quindi 3 / 1 disponibili
				if($i % 3 != 0) {
					print "
					<div class=\"container-list-server\"></div>";
					$i++;
				}

			}

			/* Controllo se il valore $i non è 1 ed è multiplo di 4, perchè deve essere usato solo per il footer del div */
			if($i != 1 && $i % 3 == 0):
				print $foo;
			endif;

		}

	}

	/**
	 * Get last report html for index
	 *
	 * @param name Nome del server
	 * @param num Numero di ultimi report da visualizzare
	 */
	public function getLastReportHtmlIndex($name, $num) {

		$sql_query = "SELECT * FROM `reporter` WHERE server='{$this->real_escape_string($name)}' AND status='1' ORDER BY `reporter`.`Time` DESC LIMIT 0,{$num}";
		$sql = $this->runQueryMysql($sql_query);

		print "
				<h4>Last report waiting:</h4>
				<ul class=\"lastreport-serverlist-dash\">";

		while($row = $sql->fetch_array())
		{

			$html = "<li>";
			$html .= "<div class=\"avatar-reported-dash\">";
			$html .= "<img src=\"{$this->getUtily->getAvatarUser($row['PlayerReport'])}\" />";
			$html .= "</div>";
			$html .= "<div class=\"nickname-reported-dash\">{$row['PlayerReport']}</div>";
			$html .= "</li>";

			print $html;

		}
		print "
				</ul>";

	}

	/* Function private to get config data
	*/
	private function getConfig($conf, $pos = null) {

		/* Check the position of the file */
		if($pos == "root" || $pos == null)

			include("config.php");
		elseif($pos == "Dir")

			include(dirname(dirname(__FILE__)).'/config.php');

		return $config[$conf];

	}
	/**
	* Get Url page complete
	*
	* @param Pos Position on the page request
	* @return Structure of the template header
	*/
	public function getUrlCurrent() {}

	/**
	* Get hash password users
	*
	*/
	public function getHasPassword() {

	}
	/**
	* Login primary function
	*
	* @param username
	* @param password
	*/
	public function authLogin($username, $password) {

		#	Salt for login:
		# 1) id session login
		# 2) useraget
		# 3) ipaddress
		# Tutto in sha512

		if(!empty($username) && !empty($password))
		{

			/*
				Proteggo da sql injection e xss anche se non sarebbe possibile passare html
				tramite controllo sessione

				Security sql injection check
				*/
			$usernameSEC = trim(strip_tags($this->real_escape_string($username)));
			$passwordSEC = trim(strip_tags($this->real_escape_string($password)));

			$passwordCRIPT = hash('sha512', $passwordSEC);
			$query = $this->queryLogin($usernameSEC, $passwordCRIPT);

			/* Numero dei risultati trovati nella query */
			$risultatoLogin = $query->num_rows;

			/* Cerco il risultato del login */
			if($risultatoLogin == 1)
			{
				# Login riuscito

				#
				/* Creo il saltid */
				$salt_id = uniqid('session_', true);
				/* Useraget */
				$useragent = $this->getUtily->getAgent();
				/* Ip address */
				$ip_adress = $this->getUtily->getIndirizzoIP();

				/* Creo la saltkey  */
				$saltSHA512 = hash('sha512', $salt_id.$useragent.$ip_adress);

				/**
				* Richiama la funzione che permette di creare la sessione
				* e aggiorno le chiavi di login
				*/
				$this->updateKeyLogin($usernameSEC, $saltSHA512, $salt_id);
				return true;

			} else
			{
				return false;
			}

		}
	}

	/**
	* Run query for login
	* Eseguo la query principale che mi permette di verificare il login
	* @param username
	* @param password
	* @return query
	*/
	private function queryLogin($username, $password) {
		return $this->runQueryMysql("SELECT * from webinterface_login WHERE username='". $username ."' AND password='". $password ."'");
	}

	/**
	*	Update key in mysql
	* and create a session
	* @param username
	* @param salt
	* @param saltOnly (questo è la chiave id)
	*/
	private function updateKeyLogin($username, $salt, $saltOnly) {

		$this->runQueryMysql("UPDATE webinterface_login SET salt_logged='". $salt ."' WHERE username='". $username ."'");
		$this->runQueryMysql("UPDATE webinterface_login SET salt_login='". $saltOnly ."' WHERE username='". $username ."'");
		$this->createSessionLogin($username, $salt, $saltOnly);

	}

	/**
	*	Update key logut
	* (Imposto il valore nullo alle key dell'utente @param username)
	* @param username
	*/
	private function updateKeyLogout($username) {

		$this->runQueryMysql("UPDATE webinterface_login SET salt_logged='' WHERE username='". $username ."'");
		$this->runQueryMysql("UPDATE webinterface_login SET salt_login='' WHERE username='". $username ."'");
	}

	/**
	* Create a session using PHP without cookie (Possibile update)
	*
	* @param username
	* @param salt
	*/
	private function createSessionLogin($username, $salt, $saltID) {

		if(!empty($username) && !empty($salt) && !empty($saltID)) {

			/* Creo la sessione per l'utente */
			$_SESSION['rg_username'] = $username;
			$_SESSION['rg_sessionSalt'] = $salt;
			$_SESSION['rg_sessionId'] = $saltID;
		}
	}

	/**
	* Get a name user logged
	*/
	public function getUsername() {
		return $_SESSION['rg_username'];
	}

	/**
	* Get a user key id
	*/
	public function getKeyID() {
		return $_SESSION['rg_sessionId'];
	}

	/**
	* Check user is logged
	*/
	public function isLogged() {

		if(isset($_SESSION['rg_username']) && isset($_SESSION['rg_sessionId']) && isset($_SESSION['rg_sessionSalt'])) {

			$session_user = $this->real_escape_string($_SESSION['rg_username']);
			$session_id = $this->real_escape_string($_SESSION['rg_sessionId']);
			$session_salt = $this->real_escape_string($_SESSION['rg_sessionSalt']);

			if($this->queryCheckLogged($session_user, $session_id, $session_salt))
			{
				return true;
			} else {

				/* If key is not valid, unset all session (and prevent error) */
				unset($_SESSION['rg_username']);
				unset($_SESSION['rg_sessionId']);
				unset($_SESSION['rg_sessionSalt']);

			}

			/* echo "<br />";
			echo $session_salt;
			echo "<br />";
			echo $session_user;
			echo "<br />";
			echo $session_id;
			echo "<br />"; */
			/* Salt corrente */
			//return array ($session_salt, $session_id, $session_user);
		}
		return false;
	}

	/**
	* Run query for check session
	* Sicurezza per il controllo della sessione
	*/
	private function queryCheckLogged($username, $session_id, $session_salt) {

		/* useragent address */
		$useragent = $this->getUtily->getAgent();
		/* Ip address */
		$ip_adress = $this->getUtily->getIndirizzoIP();

		/* Creo la saltkey  */
		$saltSHA512 = hash('sha512', $session_id.$useragent.$ip_adress);


		if(!$saltSHA512 == $session_salt) {
			return false;
		}

		$query = $this->runQueryMysql("SELECT * from webinterface_login WHERE username='". $username ."' AND salt_login='". $session_id ."' AND salt_logged='". $session_salt ."'");
		$queryresult = $query->num_rows;

		//echo $queryresult;
		if($queryresult == 1) {
			return true;
		}
		return false;

	}
	/**
	* Logout function
	*
	*/
	public function getLogOut() {

		if(isset($_SESSION['rg_username']) && isset($_SESSION['rg_sessionId']) && isset($_SESSION['rg_sessionSalt'])) {

			$session_user = $this->real_escape_string($_SESSION['rg_username']);
			$this->updateKeyLogout($session_user);

			unset($_SESSION['rg_username']);
			unset($_SESSION['rg_sessionId']);
			unset($_SESSION['rg_sessionSalt']);

			header("Location: index.php?logout=true");

		}

	}

	/**
	* Check key ID is valid for user logged
	*/
	public function checkKeyID($username, $key) {

		$query = $this->runQueryMysql("SELECT * from webinterface_login WHERE username='{$this->real_escape_string($username)}' AND salt_login='{$this->real_escape_string($key)}'");

		if($query->num_rows > 0):
			return true;
		endif;

		return false;
	}

	/**
	* Public function for add sever page
	* @param post name server
	* @return query
	*/
	public function addServer($name) {

		// Rendo i caratteri della variabile locale nome minuscoli per non creare problemi di case sensitive
		$name = strtolower($this->escape_html($name));

		// Checking if the server already exists
		$check = $this->runQueryMysql("SELECT name FROM webinterface_servers WHERE name='{$this->real_escape_string($name)}'");

		// controllo se il risultato della query è maggiore di zero, in caso ritorno con false e printo il messaggio di errore
		if($check->num_rows > 0)
		{
			print "The server already exists!";
			return false;
		}

		if( strlen($name) >= 1 )
		{

			$query = $this->runQueryMysql("INSERT INTO webinterface_servers(`ID` ,`name`) VALUES (NULL , '{$this->real_escape_string($name)}')") or die (mysqli_error($this->mysqli));
			print "Server successfully added!";
			return true;
		}	else {

			echo "Please fill the required fields (name)";
			return false;
		}

	}

	/**
	* Check server exists
	* @param name server
	*/
	public function isServerExists($name) {

		$name = strtolower($name);

		$check = $this->runQueryMysql("SELECT name FROM webinterface_servers WHERE name='{$this->real_escape_string($name)}'");
		if($check->num_rows == 1)
		{
			return true;
		}
		return false;
	}

	/**
	* Public function to delete a server
	*
	* @param post name server
	* @return query
	*/
	public function deleteServer($name) {
		return $this->runQueryMysql("DELETE FROM webinterface_servers WHERE name ='{$this->real_escape_string($name)}'");
	}

	/**
	* get int total server added
	*/
	public function getIntTotalServer() {
		$list = $this->runQueryMysql("SELECT * FROM `webinterface_servers`");
		return $list->num_rows;
	}

}
$RGWeb = new ReporterGUI();

/* Carico altre classi passando dal class root (ReporterGUI) */
Class loadClass
{
	/**
	* Load the class Utilities
    * @return variable in class Utilities
	*/
	function getUtily() {
		include("utilities.inc.php");
		return $Utilities;
	}
}

?>
