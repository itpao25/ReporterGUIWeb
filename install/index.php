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

define("RG_ROOT", "true", true);

Class ReporterGUIInstall {

  private $mysqli = false;

  public function __construct() {

    $this->getHeader();

    $mysql_host = $this->getConfig("mysql-host");
    $mysql_user = $this->getConfig("mysql-user");
    $mysql_password = $this->getConfig("mysql-password");
    $mysql_namedb = $this->getConfig("mysql-databaseName");
    $mysql_getPort = $this->getConfig("mysql-port");

    $this->mysqli = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_namedb, $mysql_getPort) or die("Errore durante la connessione " . mysqli_error($con));

    if($this->mysqli) {
      // Create tables
      $this->createTableLogin();
      $this->createTableServer();
      $this->createTableLogs();
      $this->createAdmin();
    }

  }

  private function getHeader() {
    print "<!DOCTYPE html><body><title>Install ReporterGUI</title>";
    print "<link href=\"install.css\" rel=\"stylesheet\">";
    print "<img width=\"320px\" src=\"../assets/img/logo-rgui.png\" /><br />";
  }

  /* Function private to get config data
	*/
	private function getConfig($conf) {
    include(dirname(dirname(__FILE__)).'/config.php');
    return $config[$conf];
	}

  /**
  * Run query from mysql
  */
  private function runQueryMysql($query) {
    $q = $this->mysqli->query($query);
    return $q;
  }
  /* Escape string utilty */
  public function real_escape_string($str)
  {
    return $this->mysqli->real_escape_string($str);
  }

  /**
  * Create a table 'webinterface_login'
  */
  private function createTableLogin() {

    $query = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_login'");

    if($query->num_rows !=1):

      $sql = "CREATE TABLE IF NOT EXISTS `webinterface_login` (
        `ID` int(11) unsigned NOT NULL auto_increment,
        `username` varchar(255) NOT NULL default '',
        `password` varchar(255) NOT NULL default '',
        `salt_login` varchar(255) NOT NULL default '',
        `salt_logged` varchar(255) NOT NULL default '',
        `permission` varchar(255) NOT NULL default '',
        `lastlogin` varchar(255) NOT NULL default '',
        `lastIP` varchar(255) NOT NULL default '',
        `data` varchar(8000) NOT NULL default '',
        PRIMARY KEY  (`ID`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

      $this->runQueryMysql($sql);

      print "The table `webinterface_login` was created successfully!";
      print "<br />";

    else:
      if(!isset($_POST['add-useradmin']) && !isset($_POST['add-useradmin2']) ) {
        print "The table `webinterface_login` was not created because it already exists!";
        print "<br />";
      }
    endif;

  }

  /**
  * Create a table 'webinterface_servers'
  */
  private function createTableServer() {

    $query = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_servers'");

    if($query->num_rows !=1):

      $sql = "CREATE TABLE IF NOT EXISTS `webinterface_servers` (
        `ID` int(11) unsigned NOT NULL auto_increment,
        `name` varchar(255) NOT NULL default '',
        PRIMARY KEY  (`ID`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

      $this->runQueryMysql($sql);

      print "The table `webinterface_servers` was created successfully!";
      print "<br />";

    else:

      if(!isset($_POST['add-useradmin']) && !isset($_POST['add-useradmin2']) ) {
        print "The table `webinterface_servers` was not created because it already exists!";
        print "<br />";
      }
    endif;

  }

  /**
  * Create a table 'webinterface_logs'
  * Added in 1.3
  */
  private function createTableLogs() {

    $query = $this->runQueryMysql("SHOW TABLES LIKE 'webinterface_logs'");

    if($query->num_rows !=1):

      $sql = "CREATE TABLE IF NOT EXISTS `webinterface_logs` (
      `ID` int(11) unsigned NOT NULL auto_increment,
      `action` varchar(255) NOT NULL default '',
      `username` varchar(255) NOT NULL default '',
      `IP` varchar(100) NOT NULL default '',
      `time` varchar(100) NOT NULL default '',
      PRIMARY KEY  (`ID`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

      $this->runQueryMysql($sql);

      print "The table `webinterface_logs` was created successfully!";
      print "<br />";
    else:

      if(!isset($_POST['add-useradmin']) && !isset($_POST['add-useradmin2']) ) {
        print "The table `webinterface_logs` was not created because it already exists!";
        print "<br />";
      }
    endif;


  }
  /**
  * Creo l'utente amministratore primario
  */
  private function createAdmin() {

    $query = $this->runQueryMysql("SELECT ID, permission FROM webinterface_login WHERE ID=1 AND permission='admin'");

    if($query->num_rows !=1):

      /**
      * Aggiungo il primo utente amministratore attraverso un form
      * @param post add-useradmin (username)
      * @param post add-useradmin2 (password)
      */
      if(isset($_POST['add-useradmin']) && isset($_POST['add-useradmin2']))
      {

        $username = trim(strip_tags($this->real_escape_string($_POST['add-useradmin'])));
        $password = trim(strip_tags($this->real_escape_string($_POST['add-useradmin2'])));

        // Salt private for password
        // Added in 1.3

        $salt = "w\|KT!jc@sn/@h//X";
        $password_crip = hash('sha512', $password.$salt);

        /* Check if the user already exists */
        $check = $this->runQueryMysql("SELECT ID, permission FROM webinterface_login WHERE username='".$username."'");

        if($check->num_rows == 0)
        {
          $this->runQueryMysql("INSERT INTO webinterface_login(username, password, permission) VALUES ('$username', '$password_crip', 'admin')") or die(mysqli_error($this->mysqli));
          print "The administrator account $username has been successfully created, using the password you entered ($password)";
          print "<br /><a href=\"../\">Next step</a>";

        } else
        {
          print "The user $username already exists!";
        }
      } else
      {
        print "<h4>Create the administrator account to start: </h4>";
        print "<form method=\"post\" ><input placeholder=\"Name\" type=\"text\" name=\"add-useradmin\" /> <input placeholder=\"Password\" type=\"text\" name=\"add-useradmin2\" /> <input type=\"submit\" /> </form>";
      }

    else:
      print "The first user is already created! Check in your database";
    endif;

  }


}

new ReporterGUIInstall();

?>
