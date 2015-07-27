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

if (!defined('RG_ROOT')) die();
Class RGroups
{
  public function getMenuGroup() {

    $group = $this->getGroupLogged();
    switch ($group) {
      case 'admin':
        print "<a id=\"menu-Users\" href=\"users.php\">Manager users</a>";
        print "<a id=\"menu-Update\" href=\"update.php\">Update</a>";
        break;
      case 'moderator':
        print "<a id=\"menu-Update\" href=\"update.php\">Update</a>";
      default:
        # code...
        break;
    }
  }

  /**
  * Get ID of group user logged in
  * @return int id
  */
  public function getGroupID($group) {
    switch ($group) {
      // Group admin
      case 'admin':
        return 3;
        break;
      // Group moderator
      case 'moderator':
        return 2;
        break;
      // Group helper
      case 'helper':
        return 1;
        break;
      // Error in row o in query
      default:
        return 0;
        break;
    }
  }
  /**
  * Get group user logged
  * Using session username
  */
  public function getGroupLogged() {

    global $RGWeb;
    $username = $RGWeb->real_escape_string($_SESSION['rg_username']);
    $query = $RGWeb->runQueryMysql("SELECT username, permission FROM `webinterface_login` WHERE username='{$username}'");
    $row = mysqli_fetch_assoc($query);

    /* Trim user group for fix to edit*/
    return trim($row['permission']);
  }
  /**
  * Lista degli utenti presenti nella tabella webinterface_login
  */
  public function getListUsers() {

    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT * FROM `webinterface_login`");
    print "<table style=\"width:100%\">
      <thead>
        <tr>
          <td><b>ID</b></td>
          <td><b>Username</b></td>
          <td><b>Last login</b></td>
          <td><b>Last IP</b></td>
        </tr>
      </thead>";

    while($row = $query->fetch_array())
    {
      print "
      <tr class=\"list-server-table\" >
        <td>{$row['ID']}</td>
        <td>{$row['username']}</td>
        <td>{$row['lastlogin']}</td>
        <td>{$row['lastIP']}</td>
      </tr>";
    }

    print "</table>";
  }

  /**
  * Check user logged is admin
  */
  public function isAdmin() {
  if($this->getGroupLogged() == "admin")
    return true;
  else
    return false;
  }

}

$RGroups = new RGroups();
