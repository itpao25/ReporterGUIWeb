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
*
*	Classe principale per la gestione delle pagine
*	Main class for the management of pages
*
*	Questo progetto è creato in italia, molti commenti sono stati scritti in italiano per
*	permettere di gestire meglio il codice
*
*	This project is created in Italy, many comments were written in Italian for
*	allow to better manage the code
* 1.6
* - Added file lang for make translations (If someone wants to help me effetture translation into various languages, i will insert the file lang with the name of the person who made the translation and link profile spigotmc)
* - Fixed problem on the short tag (php)
* - New is possibile delete / change status of report directly from the panel
* - Now you can set the server that one staffer can manage
* - Added the link in the notification in real time to redirect in report page
* - Fixed problem in array for search module
* - Added safety message in javascript console browser (To prevent malicious code entered by a user)
* - Improved the system to display an report
* - Introduction to statistics for-player
* - Fix problem sorting server in homepage
* 1.5
* - Added notification for new report (In real time with sound notify!)
* - You can set a time (es. milliseconds) to check in real time of the last report (for notifications)
* - Added list of report with options for order data reports
* 1.4
* - You can now edit the group and password of a user (Only you can change the user ID 1 being logged in with that)
* - Users on the list are now willing through Id from lowest to highest
* 1.3
* - Added the system log of anything happening inside the panel
* - Improved the system of permission for users
* - Improved statistics in the dashboard
* - Now for the admin can delete a user (It will not be possible to delete the user with id 1)
* - Added salt to the password! (SHA512 + salt)
* - Added the name of the user logged with the head skin (minecraft)
* - Added the possibility to change the logo, inserting their logo
*/

define("RG_ROOT", "true", true);
define("RG_INSTALL", dirname(dirname(__FILE__)). "/install", true);

Class ReporterGUI
{

  public $getUtily;
  public $getGroup;
  public $getUpdate;
  public $getNotify;
  public $getLang;
  public $getStats;

  private $mysqli = false;

  function __construct() {

    $load = new loadClass();
    $this->getUtily = $load->getUtily();
    $this->getGroup = $load->getGroup();
    $this->getUpdate = $load->getUpdate();
    $this->getNotify = $load->getNotify();
    $this->getStats = $load->getStats();

    /* Controllo se è stato installato nel mysql */
    $this->makeDB();
    $this->openConMysql();

    /* Start the session global */
    session_start();
  }

  function __destruct() {
    /* Chiudo la connessione al database */
    if($this->mysqli != false)
    $this->mysqli->close();
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
    $this->mysqli = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_namedb, $mysql_getPort);
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
    if($val == false):
      $this->getUtily->messageInstall();
    else:
      if($this->mysqli == false) $this->openConMysql();

        $query = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_servers'");
        $query2 = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_login'");
        $query3 = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_logs'");

      if($query->num_rows !=1 || $query2->num_rows !=1 || $query3->num_rows !=1)
      {
        if($this->folder_exist(RG_INSTALL))
        {
          print "<meta http-equiv=\"refresh\" content=\"0;URL=install/\">";
        } else
        {
          die("Error reading tables in database");
        }
      } else
      {
        if($this->folder_exist(RG_INSTALL))
        $this->getUtily->messageInstallFolder();

      }
    endif;
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

    print "<table style=\"width:100%\">
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
      print "<tr class=\"list-server-table\" data-href=\"view-report.php?server={$row['name']}\">
      <td>{$row['name']}</td>
      <td>{$this->getTotalReport($row['name'])}</td>
      <td>{$this->getCompleteReport($row['name'])}</td>
      <td>{$this->getWaitingReport($row['name'])}</td>
      </tr>";

    }

    print "</table>";
  }

  /**
  * Get number of total report for server
  *
  * @param name
  * @return int
  */
  public function getTotalReport($name = null)
  {
    if($name != null) {
      $name = $this->real_escape_string($name);
      $int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."'");
    } else {
      $int = $this->runQueryMysql("SELECT * FROM `reporter`");
    }
    return $int->num_rows;
  }

  /**
  * Get number of report complete for server
  *
  * @param name
  * @return int
  */
  public function getCompleteReport($name) {
    $name = $this->real_escape_string($name);
    $int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."' AND status='2'");
    return $int->num_rows;
  }

  /**
  * Get number of report Waiting
  * @param name
  * @return int
  */
  public function getWaitingReport($name) {
    $name = $this->real_escape_string($name);
    $int = $this->runQueryMysql("SELECT * FROM `reporter` WHERE `server`='". $name ."' AND status='1'");
    return $int->num_rows;
  }

  /**
  * Lista dei server da visualizzare in homepage
  */
  public function getListIndex() {
    /* Order by id (added since 1.6) */
    $order = "ORDER BY `webinterface_servers`.`ID` ASC";
    $list = $this->runQueryMysql("SELECT * FROM `webinterface_servers` $order");

    $head = "<div class=\"list-server-dash\">";
    $foo = "</div>";

    $totali = $list->num_rows;

    if($totali == 0) {
      print "<a class=\"link_clear\" href=\"add-server.php\">No server present, add one?</a>";
    }
    $conto = 0;
    for($i = 1; $row = $list->fetch_array(); $i++) {
      $conto++;
      if($i == 1 || $i == 4) {
        print $head;
      }

      print "<div class=\"container-list-server\">";

      print "
        <div class=\"list-server-dash_name\">
        <h3>
          <a href=\"view-report.php?server={$row['name']}\">
          {$row['name']}
          </a>
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
      if($conto == $totali && $i != 3)
      {
        print "<div class=\"container-list-server\"></div>";
        if($i != 3) {
          print "<div class=\"container-list-server\"></div>";
          $conto = "int";
        } else {
          $conto = "int";
        }
      }
      if($conto == "int" || $i == 3) {
        print $foo;
        $i = 0;
      }
    }
  }

  /**
  * Get last report html for index
  *
  * @param name Nome del server
  * @param num Numero di ultimi report da visualizzare
  */
  public function getLastReportHtmlIndex($name, $num) {

    $name = mysqli_real_escape_string($this->mysqli, $name);
    $sql_query = "SELECT * FROM `reporter` WHERE server='$name' AND status='1' ORDER BY `reporter`.`Time` DESC LIMIT 0,{$num}";
    $sql = $this->runQueryMysql($sql_query);
    $totalreport = $sql->num_rows;

    print "<h4>Last report waiting:</h4><ul class=\"lastreport-serverlist-dash\">";
    if($totalreport == 0) {
      print "<i>No report for this server!</i>";
    }
    while($row = $sql->fetch_array())
    {

      $html = "<li>";
      $html .= "<a href=\"view-report.php?id={$row['ID']}\">";
      $html .= "<div class=\"avatar-reported-dash\">";
      $html .= "<img src=\"{$this->getUtily->getAvatarUser($row['PlayerReport'])}\" />";
      $html .= "</div>";
      $html .= "<div class=\"nickname-reported-dash\">{$row['PlayerReport']}</div>";
      $html .= "</a>";
      $html .= "</li>";

      print $html;

    }
    print "</ul>";

  }

  /* Function private to get config data
  */
  public function getConfig($conf, $pos = null) {

    /* Check the position of the file */
    if($pos == "root" || $pos == null):
      include("config.php");
    elseif($pos == "Dir"):
      include(dirname(dirname(__FILE__)).'/config.php');
    endif;

    return $config[$conf];

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

      // Salt private for password
      // Added in 1.3

      $salt = "w\|KT!jc@sn/@h//X";
      $passwordCRIPT = hash('sha512', $passwordSEC.$salt);
      $query = $this->queryLogin($usernameSEC, $passwordCRIPT);

      /* Numero dei risultati trovati nella query */
      $risultatoLogin = $query->num_rows;

      /* Cerco il risultato del login */
      if($risultatoLogin == 1)
      {

        # Login riuscito

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
        $this->updateLastLogin($usernameSEC);
        $this->updateLastIP($usernameSEC);
        $this->addLogs("User logged in");

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
  *	Update last login for user
  * @param username User is logged
  */
  private function updateLastLogin($username) {
    $time = date('Y-m-d H:i', time());
    $this->runQueryMysql("UPDATE webinterface_login SET lastlogin='{$time}' WHERE username='". $username ."'");
  }

  /**
  *	Update last ip for client user
  * @param username User is logged
  */
  private function updateLastIP($username) {
    $ip_adress = $this->getUtily->getIndirizzoIP();
    $this->runQueryMysql("UPDATE webinterface_login SET lastIP='{$ip_adress}' WHERE username='". $username ."'");
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

  /* Get id user logged*/
  public function getIDLogged() {

    $username = $this->real_escape_string($_SESSION['rg_username']);
    $query = $this->runQueryMysql("SELECT ID, username FROM `webinterface_login` WHERE username='{$username}'");
    $row = mysqli_fetch_assoc($query);

    return $row['ID'];
  }

  /* Get group user */
  public function getGroupUser($username) {

    $username = $this->real_escape_string($username);
    $query = $this->runQueryMysql("SELECT ID, permission FROM `webinterface_login` WHERE ID='{$username}'");
    $row = mysqli_fetch_assoc($query);

    return $row['permission'];
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
  * @return redirect in index.php with param get logout=true
  */
  public function getLogOut() {

    if(isset($_SESSION['rg_username']) && isset($_SESSION['rg_sessionId']) && isset($_SESSION['rg_sessionSalt'])) {

      $session_user = $this->real_escape_string($_SESSION['rg_username']);
      $this->updateKeyLogout($session_user);
      $this->addLogs("User logged out");

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

    $username = mysqli_real_escape_string($this->mysqli, $username);
    $key = mysqli_real_escape_string($this->mysqli, $key);
    $query = $this->runQueryMysql("SELECT * from webinterface_login WHERE username='$username' AND salt_login='$key'");

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

    if($this->isLogged() == false) {
      return false;
    }
    // Rendo i caratteri della variabile locale nome minuscoli per non creare problemi di case sensitive
    $name = strtolower(mysqli_real_escape_string($this->mysqli, $this->escape_html($name)));

    if( strlen($name) >= 1 )
    {
      // Checking if the server already exists
      $check = $this->runQueryMysql("SELECT name FROM webinterface_servers WHERE name='$name'");

      // controllo se il risultato della query è maggiore di zero, in caso ritorno con false e printo il messaggio di errore
      if($check->num_rows > 0)
      {
        print "The server already exists!";
        return false;
      }

      $query = $this->runQueryMysql("INSERT INTO webinterface_servers(`ID` ,`name`) VALUES (NULL , '$name')") or die (mysqli_error($this->mysqli));
      $this->addLogs("Server added ({$name})");

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

    $name = strtolower(mysqli_real_escape_string($this->mysqli, $name));
    $check = $this->runQueryMysql("SELECT name FROM webinterface_servers WHERE name='$name'");
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

    /* Check user logged is admin */
    if($this->getGroup->isAdmin() == false) {
    return;
    }
    $name = mysqli_real_escape_string($this->mysqli, $name);
    $this->addLogs("Deleted server ({$name})");
    return $this->runQueryMysql("DELETE FROM webinterface_servers WHERE name ='$name'");
  }

  /**
  * get int total server added
  */
  public function getIntTotalServer() {
    $list = $this->runQueryMysql("SELECT ID FROM `webinterface_servers`");
    return $list->num_rows;
  }

  /**
  * get int total report
  */
  public function getIntTotalReport() {
    $list = $this->runQueryMysql("SELECT ID FROM `reporter`");
    return $list->num_rows;
  }

  /**
  * get int total report waiting
  */
  public function getIntTotalReportWaiting() {
    $list = $this->runQueryMysql("SELECT * FROM `reporter` WHERE status='1'");
    return $list->num_rows;
  }

  /**
  * get int total report complete
  */
  public function getIntTotalReportComplete() {
    $list = $this->runQueryMysql("SELECT * FROM `reporter` WHERE status='2'");
    return $list->num_rows;
  }

  /* Check report id is exist */
  public function isReportExist($id) {

    if(!is_numeric($id)) {
      return false;
    }

    $id = $this->real_escape_string($id);
    $sql_query = $this->runQueryMysql("SELECT * FROM `reporter` WHERE ID={$id}");

    if($sql_query->num_rows == 1) {
      return true;
    }
    return false;
  }

  /**
  * Check username is reported At least once
  * @since 1.6
  * @param Username
  * @return int true of false
  */
  public function isUserReported($username) {
    $user = $this->real_escape_string($username);
    $query = $this->runQueryMysql("SELECT PlayerReport FROM `reporter` WHERE `PlayerReport`='$user'");
    $result = $query->num_rows > 0 ? true : false;
    return $result;
  }

  /**
  * Get report info
  * @param id
  * @return
  * 0 -> ID
  * 1 -> namePlayerReported
  * 2 -> motivazione (rason)
  * 3 -> nomeplayerFrom
  * 4 -> worldTarget
  * 5 -> worldFrom
  * 6 -> Time
  * 7 -> server
  * 8 -> status
  */
  public function getReportInfo($id) {

    $query = $this->runQueryMysql("SELECT * FROM `reporter` WHERE ID={$this->real_escape_string($id)}");
    $queryArray = mysqli_fetch_assoc($query);

    $id = $queryArray['ID'];
    $PlayerReport = $queryArray['PlayerReport'];
    $PlayerFrom = $queryArray['PlayerFrom'];
    $reason = $queryArray['Reason'];
    $WorldReport = $queryArray['WorldReport'];
    $WorldFrom = $queryArray['WorldFrom'];
    $time = $queryArray['Time'];
    $server = $queryArray['server'];
    $status = $queryArray['status'];

    return array ($id, $PlayerReport, $PlayerFrom, $reason, $WorldReport, $WorldFrom, $time, $server, $status);

  }

  /**
  * Public function for add user
  * @param post name user
  * @return query
  */
  public function addUsers($name, $password, $permission) {

    // Sicurezza all'interno della funzione, utilizzata in caso di escape della prima
    if($this->isLogged() == false) {
      return false;
    }

    $name = trim(mysqli_real_escape_string($this->mysqli, $this->escape_html($name)));
    $name = $this->getUtily->clean($name);

    $password = trim(mysqli_real_escape_string($this->mysqli, $this->escape_html($password)));
    $permission = trim(mysqli_real_escape_string($this->mysqli, $this->escape_html($permission)));
    if( strlen($name) >= 1 || strlen($password) >= 1 )
    {
      // Checking if the users already exists
      $check = $this->runQueryMysql("SELECT username FROM webinterface_login WHERE username='{$name}'");
      if($check->num_rows > 0)
      {
        print "The user already exists!";
        return false;
      }
      if($this->getGroup->getGroupID($permission) == 0)
      {
        print "Error to check group user!";
        return false;
      }

      // Salt private for password
      // Added in 1.3

      $salt = "w\|KT!jc@sn/@h//X";
      $passwordCRIPT = hash('sha512', $password.$salt);
      $query = $this->runQueryMysql("INSERT INTO webinterface_login(`ID` ,`username`,`password`,`permission`) VALUES (NULL , '$name', '$passwordCRIPT', '$permission')") or die (mysqli_error($this->mysqli));
      print "User successfully added!";
      $this->addLogs("Added user ({$name}), group {$permission}");

      return true;
    }	else {
      echo "Please fill the required fields";
      return false;
    }

  }

  /**
  * Public function for delete user
  */
  public function deleteUser($id) {
    if($this->getGroup->isAdmin() == false) {
      return;
    }
    $uque = mysqli_real_escape_string($this->mysqli, $id);
    $this->addLogs("User eliminated (ID: {$id})");
    return $this->runQueryMysql("DELETE FROM webinterface_login WHERE ID={$uque}");
  }

  # Log manager
  /* Table log */
  private function getTableLog() {
    return "webinterface_logs";
  }
  /* Add logs to mysql*/
  public function addLogs($action) {

    // Username
    $username = $this->getUsername();

    // ID
    $query = $this->runQueryMysql("SELECT ID,username FROM {$this->getTableLog()} WHERE username='$username'");
    $queryArray = mysqli_fetch_assoc($query);
    $id = $queryArray['ID'];
    // Time
    $time = date('Y-m-d H:i', time());
    // IP
    $ip = $this->getUtily->getIndirizzoIP();
    $query = $this->runQueryMysql("INSERT INTO webinterface_logs(`ID` ,`action`,`username`,`IP`, `time`) VALUES (NULL , '$action', '$username', '$ip', '$time')") or die (mysqli_error($this->mysqli));

  }

  /**
  * Get all report for server
  * Called in view-report.php
  * @since 1.5
  * @param name - name of server for get all reports
  * @param option - option for filter / order reports
  */
  public function outputReportServer($name = null, $option = null)
  {

    // Default option
    static $DEFAULT_OPTION_SORT = array("ID", "PlayerReport","PlayerFrom", "Reason", "WorldReport", "WorldFrom", "Time", "status");
    if($name == null) {
      $DEFAULT_OPTION_SORT[] = "server";
    }
    static $DEFAULT_OPTION_ORDER = array("ASC","DESC");
    static $DEFAULT_OPTION_MAXINT = array(20, 50, 100, 200);

    // Escape name
    $name = $this->real_escape_string($name);

    // Controllo se il nome corrisponde ad un server
    if($name != null) {
      if($this->isServerExists( $name ) == false) return;
    }
    // Controllo se le opzioni della funzioni non sono nulle
    // Per prevenire errori
    $isOptions = is_array( $option ) || is_object( $option ) && $option != null;
    if( $isOptions ) {

      // UHM, c'è un pò di confusione qua? LOL

      # Options
      $page = isset($option['page']) && is_numeric($option['page']) ? $option['page'] : 1;
      $sort = in_array($option['sort'], $DEFAULT_OPTION_SORT) ? $option['sort'] : "ID";
      $order = in_array($option['order'], $DEFAULT_OPTION_ORDER) ? $option['order'] : "DESC";
      $reportPerPage = is_numeric($option['maxint']) && in_array($option['maxint'], $DEFAULT_OPTION_MAXINT) ? $option['maxint'] : 20;
      # Search query
      $isSearch = isset($option['search']) && in_array($option['search'], $DEFAULT_OPTION_SORT) && isset($option['keywords']) ? true : false;
      $search = isset($option['search']) && in_array($option['search'], $DEFAULT_OPTION_SORT) ? $option['search'] : null;
      # keywords for search
      $keywords = isset($option['keywords']) ? $option['keywords'] : null;

      # Pagination
      $escape_search = $this->real_escape_string($keywords);
      $escape_page = $this->real_escape_string($page);
      $primisReportPerPage = $reportPerPage * ($escape_page - 1);

      if($isSearch != true) {
        if($name != null):
          $query = "SELECT * FROM `reporter` WHERE server='{$name}' ORDER BY $sort $order LIMIT $primisReportPerPage, $reportPerPage ";
        else:
          // Search without param server
          // Since 1.6
          $query = "SELECT * FROM `reporter` ORDER BY $sort $order LIMIT $primisReportPerPage, $reportPerPage ";
        endif;
      } else {
        # Query for search reports
        /* Check if search type is ID and Keyword is not a number */
        if($search == "ID" && !is_numeric($escape_search)) print "<div class=\"messaggio-errore\">You can only enter a number for the id field!</div><br/>";
        if($name != null):
          $query = "SELECT * FROM `reporter` WHERE server='{$name}' AND {$search} LIKE '%".$escape_search."%' ORDER BY $sort $order LIMIT $primisReportPerPage, $reportPerPage ";
          $querycount = $this->runQueryMysql("SELECT * FROM `reporter` WHERE server='{$name}' AND {$search} LIKE '%".$escape_search."%' ");
          // echo "Chiamo quando è specificato il server";
        else:
          // Search without param server
          // Since 1.6
          $query = "SELECT * FROM `reporter` WHERE {$search} LIKE '%".$escape_search."%' ORDER BY $sort $order LIMIT $primisReportPerPage, $reportPerPage ";
          $querycount = $this->runQueryMysql("SELECT * FROM `reporter` WHERE {$search} LIKE '%".$escape_search."%'");
          // echo "Chiamo il cerca quando non è specificato il server";
        endif;
      }
      $report = $this->runQueryMysql($query);

      // Int total report for server
      if($name != null && $isSearch != true)
      {
        $total = $this->getTotalReport($name);
      } elseif($isSearch == true) {
        $total = $querycount->num_rows;
      } else {
        $total = $this->getTotalReport();
      }


      # Funzione per ordinare i report
      # Riordino la colonna per sort
      if($name == null && $report->num_rows != 0 || $total != 0 || $isSearch == true):

        print "<div class=\"filter-reports-list\">";
        print "<h4>Filter reports</h4><br />";
        print "<form method=\"GET\" action=\"view-report.php\" >";
        print "<div style=\"margin-left: 0px\" class=\"select-filter\">";
        print "Sort: <select name=\"sort\">";
        foreach($DEFAULT_OPTION_SORT as $key)
        {
          if($sort == $key):
            echo "<option value=\"$key\" selected =\"selected\"> $key </option>";
          else:
            echo "<option value=\"$key\"> $key </option>";
          endif;
        }
        print "</select></div>";
        # Riordino la colonna per order
        print "<div class=\"select-filter\">";
        print "Order by: <select name=\"order\" >";
        foreach($DEFAULT_OPTION_ORDER as $key)
        {
          if($order == $key):
            echo "<option value=\"$key\" selected =\"selected\"> $key </option>";
          else:
            echo "<option value=\"$key\"> $key </option>";
          endif;
        }
        print "</select></div>";
        # Numero dei report per pagina
        print "<div class=\"select-filter\">";
        print "View reports for page: <select name=\"maxint\">";
        foreach($DEFAULT_OPTION_MAXINT as $key)
        {
          print $key;
          if($reportPerPage == $key):
            echo "<option value=\"$key\" selected =\"selected\"> $key </option>";
          else:
            echo "<option value=\"$key\"> $key </option>";
          endif;
        }
        print "</select></div>";
        if($name != null) {
          print "<input type=\"hidden\" name=\"server\" value=\"{$name}\">";
        }
        print "<input type=\"hidden\" name=\"page\" value=\"{$escape_page}\">";
        if($isSearch == true) {
          print "<input type=\"hidden\" name=\"search\" value=\"{$search}\">";
          print "<input type=\"hidden\" name=\"keywords\" value=\"{$escape_search}\">";
        }
        print "<input type=\"submit\" value=\"Apply\" />";
        print "</form>";
        print "<span>". str_replace("%int%", $total, $this->getLang("search-resultint", "ret")). "</span>";
        print "</div><br />";

        endif;

      // Navigation bar
      // Ciclo che permette di aggiungere alla variabile nav il
      // contenuto del menu che dovrà essere mostrato per la barra di navigazione
      $int = $report->num_rows;

      if($name == null && $report->num_rows != 0 || $total != 0):
        $nav = "<div class=\"container-nav\"><div class=\"navigationbar\">";
        /* Il valore int $total / $reportPerPage equivale al numero totale di report per il numero massimo di visualizzazioni nella pagina*/
        $num = ceil($total / $reportPerPage);

        // Link for next / prev page
        $url = $this->getUtily->selfURL();
        $newurl = preg_replace("/&page=[0-9]+/", "", $url);

        // Fix problem with ? for get param
        $newurl = strpos($newurl, '?') ? $newurl : $newurl."?";

        if($escape_page - 1 > 0) {
          $pag = $escape_page -1;
          $nav .= "<a href='{$newurl}&page={$pag}'>Prev</a>";
        }
        for ($i = 1; $i <= ceil($total / $reportPerPage); $i++) {
          $nav .= ($i != $escape_page ) ? "<a href='{$newurl}&page={$i}'>{$i}</a>": "<a class=\"active\" href='{$newurl}&page={$i}'>{$i}</a>";
        }
        if($num != $escape_page && $escape_page + 1) {
          $pag = $escape_page +1;
          $nav .= "<a href='{$newurl}&page={$pag}'>Next</a>";
        }

        $nav .= "</div></div>";
        print $nav;
      endif;

    } else
    {
      throw new Exception("Le operazioni che sta facendo la funzione outputReportServer() non sono corrette");
    }
    $headerServer = $name != null ? "" : "<td><b>Server</b></td>";
    $head = "<table style=\"width:100%\">
    <thead>
    <tr>
    <td><b>ID</b></td>
    <td><b>Player Report</b></td>
    <td><b>By</b></td>
    <td><b>Reason</b></td>
    <td><b>Time</b></td>
    <td><b>In world (reported)</b></td>
    <td><b>In world (by)</b></td>
    <td><b>Status</b></td>
    {$headerServer}
    </tr>
    </thead>";
    print $head;

    $num = 0;
    if($report != null) {
      while($row = $report->fetch_array())
      {
        $reason = strip_tags($row['Reason']);
        $html = "<tr class=\"list-server-table\" data-href=\"view-report.php?id={$row['ID']}\">";
        $html .= "<td>{$row['ID']}</td>";
        $html .= "<td>{$row['PlayerReport']}</td>";
        $html .= "<td>{$row['PlayerFrom']}</td>";
        $html .= "<td>{$reason}</td>";
        $html .= "<td>{$row['Time']}</td>";
        $html .= "<td>{$row['WorldReport']}</td>";
        $html .= "<td>{$row['WorldFrom']}</td>";
        $html .= "<td>{$row['status']} - ". $this->getUtily->convertStatusString($row['status'], "ret")."</td>";
        if($name == null) $html .= "<td>{$row['server']}</td>";
        $html .= "</tr>";

        print $html;
        $num++;
      }
    }
    if($num == 0) {
      // No report found
      print "<tr class=\"list-server-table\" >
      <td >No report present</td>
      <td></td>
      <td></td>
      <td></td>
      </tr>";
    }
    print "</table>";

    if($name == null && $report->num_rows != 0 || $total != 0):
      /* Navigation bar */
      print $nav;
    endif;
    /* Form for search report */
    if($name == null && $report->num_rows != 0 || $total != 0 || $isSearch == true):
      print $this->searchReportListServer($name, $search, $keywords);
    endif;
  }

  /**
  * Form for search reports (using in function outputReportServer)
  * @since 1.5
  */
  private function searchReportListServer($server = null, $type = null, $keywords = null) {
    static $DEFAULT_OPTION_SORT = array("ID", "PlayerReport","PlayerFrom", "Reason", "WorldReport", "WorldFrom", "Time", "status");
    if($server == null) {
      $DEFAULT_OPTION_SORT[] = "server";
    }
    print "<br /><div class=\"filter-reports-list\">";
    print "<h4>Search report</h4><br />";
    print "<form method=\"GET\" action=\"view-report.php\" >";
    # Search for columbs
    print "<select style=\"width: 300px;display: inline;\" name=\"search\" >";
    foreach($DEFAULT_OPTION_SORT as $key)
    {
      echo "<option value=\"$key\"> $key </option>";
      if($type != null):
        if($type == $key):
          echo "<option value=\"$key\" selected =\"selected\" > $key </option>";
        endif;
      endif;
    }
    print "</select>";
    # Keywords
    if($keywords != null) {
      $placeholder = $this->getUtily->clean($keywords);
    } else {
      $placeholder = "Keywords";
    }
    print "<input style=\"display: inline;width: 838px;margin-left: 10px;\" type=\"text\" name=\"keywords\" placeholder=\"{$placeholder}\"/>";
    # Server
    if($server != null) {
      print "<input type=\"hidden\" name=\"server\" value=\"{$server}\" />";
    }
    # Submit button
    print "<input type=\"submit\" value=\"Search\" />";
    print "</form>";
    print "</div>";
  }

  /**
  * Function for delete report
  * @since 1.6
  */
  public function deleteRepo($id)
  {
    if($this->isReportExist($id) == false) {
      return;
    }
    if($this->getGroup->isHelper()) {
      return;
    }
    $id = $this->real_escape_string($id);
    $sql_query = $this->runQueryMysql("DELETE FROM `reporter` WHERE ID={$id}");
    $this->addLogs("Delete report #$id");
  }

  /**
  * Function for edit report
  * @param id -> Id del report che deve essere modifcato
  * @param action -> Azione che deve essere eseguita per modificare il report
  * @since 1.6
  */
  public function editReport($id, $action)
  {
    // Check report is exist
    if($this->isReportExist($id) == false) {
      return;
    }
    // Check user is not in group "helper"
    if($this->getGroup->isHelper() == true) {
      return;
    }
    // Sql injection security
    $id = $this->real_escape_string($id);

    switch ($action) {
      // switch for action - set status 1 (waiting)
      case 'status1':
        $sql_query = $this->runQueryMysql("UPDATE `reporter` SET status=1 WHERE ID={$id}");
        break;
      // switch for action - set status 2 (complete)
      case 'status2':
        $sql_query = $this->runQueryMysql("UPDATE `reporter` SET status=2 WHERE ID={$id}");
        break;
      default:
        return;
        break;
    }
  }


  /**
  * Load the class for language
  * @since 1.6
  */
  public function getLang($STRING = null, $type = null)
  {
    // Include file lang.inc.php
    include("lang/lang.inc.php");
    static $lang_tag = "lang";
    // Controllo se l'array riguardante è settata
    // Altrimenti ritorno la funzione
    if( $STRING != null && isset( $lang[$lang_tag][$STRING] ) )
    {
      if($type == null):
        print $lang[$lang_tag][$STRING];
      elseif($type == "ret"):
        return $lang[$lang_tag][$STRING];
      endif;
    }
    // Errore nel trovare l'array nel file lang
    return;
  }
}
$RGWeb = new ReporterGUI();

/* Carico altre classi passando dal class root (ReporterGUI) */
Class loadClass
{
  /**
  * Load class Utilities
  * @return Class _RGUtilities
  */
  function getUtily() {
    include("utilities.inc.php");
    return new _RGUtilities();
  }
  /**
  * Load class for group user
  * @return Class _RGroups
  */
  function getGroup() {
    include("groups.inc.php");
    return new _RGroups();
  }

  /**
  * Load class for update
  * @return Class _RGUpdate
  */
  function getUpdate() {
    include("update.inc.php");
    return new _RGUpdate();
  }
  /**
  * Load class for notify
  * @since 1.5
  */
  function getNotify() {
    include("notify.inc.php");
    return new _RGNotify();
  }
  /**
  * Load class stats for player
  * @since 1.6
  * @return class _RGStats
  */
  function getStats() {
    include("stats.inc.php");
    return new _RGStats();
  }
}

$ClassLoader = new loadClass();
?>
